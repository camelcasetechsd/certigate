<?php

namespace IssueTracker\Model;

use IssueTracker\Service\IssueCategories;
use IssueTracker\Entity\Issue as IssuesEntity;
use IssueTracker\Entity\IssueCategory as CatergoriesEntity;
use Doctrine\Common\Collections\Criteria;
use Zend\File\Transfer\Adapter\Http;
use Utilities\Service\Random;
use Utilities\Service\Status;
use Doctrine\ORM\EntityRepository;
use Zend\Authentication\AuthenticationService;
use Users\Entity\Role;
use Notifications\Service\MailSubjects;
use Notifications\Service\MailTemplates;
use System\Service\Cache\CacheHandler;
use System\Service\Settings;
use Utilities\Service\Paginator\PaginatorAdapter;
use Zend\Paginator\Paginator;

class Issues
{

    use \Utilities\Service\Paginator\PaginatorTrait;

    /**
     * path to save issue attachments 
     */
    const ISSUE_ATTACHMENT_PATH = 'public/upload/IssueAttachments/';

    /*
     *
     * @var Utilities\Service\Query\Query 
     */

    protected $query;

    /**
     *
     * @var Notifications\Service\Notification
     */
    protected $notification;

    /**
     *
     * @var Utilities\Service\Random 
     */
    protected $random;

    /**
     *
     * @var System\Service\Cache\CacheHandler
     */
    protected $systemCacheHandler;
    protected $router;

    /**
     * Set needed properties
     * 
     * 
     * @access public
     * 
     * @param Utilities\Service\Query\Query $query
     * @param Notifications\Service\Notification $notification
     * @param System\Service\Cache\CacheHandler $systemCacheHandler
     * @param Zend\Mvc\Router\RouteInterface $router
     */
    public function __construct($query, $notification, $systemCacheHandler, $router)
    {
        $this->query = $query;
        $this->notification = $notification;
        $this->systemCacheHandler = $systemCacheHandler;
        $this->random = new Random();
        $this->paginator = new Paginator(new PaginatorAdapter($query, "IssueTracker\Entity\Issue"));
        $this->router = $router;
    }

    /**
     * Saving reported issues
     * @param type $data
     * @param IssuesEntity $issueObj
     */
    public function saveIssue($data, $issueObj = null)
    {
        if ($issueObj == null) {
            $issueObj = new IssuesEntity();
        }
        $parent = $this->query->findOneBy('IssueTracker\Entity\IssueCategory', array(
            'id' => $data['parent']
        ));
        $auth = new AuthenticationService();
        $storage = $auth->getIdentity();
        $issueObj->setCreated();
        $issueObj->setUser($this->query->findOneBy('Users\Entity\User', array('id' => $storage['id'])));
        $issueObj->setCategory($parent);
        $paths = array();
        foreach ($data['filePath'] as $file) {
            if (!empty($file['name'])) {
                $uploadResult = $this->uploadAttachment($file['name'], self::ISSUE_ATTACHMENT_PATH);
                array_push($paths, $uploadResult);
            }
        }
        $issueObj->setFilePath(base64_encode(serialize($paths)));
        $issueObj->setStatus(Status::STATUS_ACTIVE);
        $this->query->setEntity('IssueTracker\Entity\Issue')->save($issueObj, $data);
        $this->sendMails($issueObj);
    }

    /**
     * upload issue attachments
     * @param type $filename
     * @param type $attachmentPath
     * @return string
     */
    private function uploadAttachment($filename, $attachmentPath)
    {
        $uploadResult = null;
        $upload = new Http();
        $upload->setDestination($attachmentPath);
        try {
            // upload received file(s)
            $upload->receive($filename);
        } catch (\Exception $e) {
            return $uploadResult;
        }
        //This method will return the real file name of a transferred file.
        $name = $upload->getFileName($filename);
        //This method will return extension of the transferred file
        $extention = pathinfo($name, PATHINFO_EXTENSION);
        //get random new name
        $newName = $this->random->getRandomUniqueName() . '_' . date('Y.m.d_h:i:sa');
        $newFullName = $attachmentPath . $newName . '.' . $extention;
        // rename
        rename($name, $newFullName);
        $uploadResult = $newFullName;
        return $uploadResult;
    }

    /**
     * list all issues both active and closed except inactive('deleted') issues
     * @return array
     */
    public function listIssues()
    {
        $repository = $this->query->entityManager;
        $queryBuilder = $repository->createQueryBuilder();
        $expr = $queryBuilder->expr();
        $queryBuilder->select('i')
                ->from('IssueTracker\Entity\Issue', 'i');

        return $queryBuilder->getQuery()->getResult();
    }

    public function getIssue($issueId)
    {
        return $this->query->findOneBy('IssueTracker\Entity\Issue', array(
                    'id' => $issueId
        ));
    }

    /**
     * prepare issues texts for view
     * @param Array | IssueTracker\Entity\Issue $issues
     * @return Array | IssueTracker\Entity\Issue 
     */
    public function prepareIssuesToView($issues)
    {
        foreach ($issues as $issue) {

            $issue->filePath = unserialize(base64_decode($issue->filePath));
            // preparing status text
            switch ($issue->getStatus()) {
                case 0:
                    $issue->statusText = (Status::STATUS_CLOSED_TEXT);
                    break;
                case 1:
                    $issue->statusText = (Status::STATUS_ACTIVE_TEXT);
                    break;
            }
            // preparing parent tree
            $issue = $this->getParentTree($issue);
        }

        return $issues;
    }

    /**
     * creating 3 depth tree for parents if existed
     * @param IssueTracker\Entity\Issue $issue
     * @return IssueTracker\Entity\Issue $issue
     */
    private function getParentTree($issue)
    {   // getting issue category
        $issue->parent3 = $issue->getCategory();
        if ($issue->parent3 != null) {
            $issue->parent2 = $issue->parent3->getParent();
            if ($issue->parent2 != null) {
                $issue->parent1 = $issue->parent2->getParent();
            }
        }
        return $issue;
    }

    public function deleteIssue($issueId)
    {
        $issue = $this->query->findOneBy('IssueTracker\Entity\Issue', array(
            'id' => $issueId
        ));
        $this->query->remove($issue);
    }

    public function closeIssue($issueId)
    {
        $issue = $this->query->findOneBy('IssueTracker\Entity\Issue', array(
            'id' => $issueId
        ));
        $issue->setStatus(Status::STATUS_CLOSED);
        $this->query->save($issue);
    }

    public function reopenIssue($issueId)
    {
        $issue = $this->query->findOneBy('IssueTracker\Entity\Issue', array(
            'id' => $issueId
        ));
        $issue->setStatus(Status::STATUS_ACTIVE);
        $this->query->save($issue);
    }

    public function validateUser()
    {
        $auth = new AuthenticationService();
        $storage = $auth->getIdentity();
        if (in_array(Role::ADMIN_ROLE, $storage['roles'])) {
            return true;
        }
        return false;
    }

    public function getCurrentUser()
    {
        $auth = new AuthenticationService();
        $storage = $auth->getIdentity();
        return $this->query->findOneBy('Users\Entity\User', array(
                    'id' => $storage['id']
        ));
    }

    /**
     * Send mail
     * 
     * @access private
     * @param IssueTracker\Entity\Issue $issueObj issue data
     * @throws \Exception From email is not set
     * @throws \Exception Admin email is not set
     */
    private function sendMails($issueObj)
    {
        $forceFlush = (APPLICATION_ENV == "production" ) ? false : true;
        $cachedSystemData = $this->systemCacheHandler->getCachedSystemData($forceFlush);
        $settings = $cachedSystemData[CacheHandler::SETTINGS_KEY];

        if (array_key_exists(Settings::SYSTEM_EMAIL, $settings)) {
            $from = $settings[Settings::SYSTEM_EMAIL];
        }

        if (array_key_exists(Settings::ADMIN_EMAIL, $settings)) {
            $adminEmail = $settings[Settings::ADMIN_EMAIL];
        }

        if (!isset($from)) {
            throw new \Exception("From email is not set");
        }

        if (!isset($adminEmail)) {
            throw new \Exception("Admin email is not set");
        }

        $auth = new AuthenticationService();
        $storage = $auth->getIdentity();
        $templateParameters = array(
            'issueUrl' => $this->router->assemble(array("issueId" => $issueObj->getId()), array('name' => 'viewIssues', 'force_canonical' => true))
        );

        $adminTemplateName = MailTemplates::ADMIN_NEW_ISSUE;
        $userTemplateName = MailTemplates::USER_NEW_ISSUE;
        $subject = MailSubjects::NEW_ISSUE;
        $AdminNotificationMailArray = array(
            'to' => $adminEmail,
            'from' => $from,
            'templateName' => $adminTemplateName,
            'templateParameters' => $templateParameters,
            'subject' => $subject,
        );
        $userNotificationMailArray = array(
            'to' => $storage['email'],
            'from' => $from,
            'templateName' => $userTemplateName,
            'templateParameters' => $templateParameters,
            'subject' => $subject,
        );
        $this->notification->notify($AdminNotificationMailArray);
        $this->notification->notify($userNotificationMailArray);
    }

    public function filterIssues()
    {
        $adapter = $this->paginator->getAdapter();
        $adapter->setQuery($this->query->setEntity("IssueTracker\Entity\Issue")->entityRepository);
        $adapter->setMethodName("getIssues");
        $adapter->setParameters(array(
            "issueModelObj" => $this
        ));
    }

}
