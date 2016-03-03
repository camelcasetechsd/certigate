<?php

namespace Courses\Model;

use Utilities\Service\Status;
use Zend\Filter\File\RenameUpload;
use Utilities\Form\FormElementErrors;
use System\Service\Cache\CacheHandler;
use System\Service\Settings;
use Notifications\Service\MailTempates;
use Notifications\Service\MailSubjects;

/**
 * Resource Model
 * 
 * Handles Resource Entity related business
 * 
 * 
 * @property Utilities\Service\Query\Query $query
 * @property Zend\Log\Logger $logger
 * @property System\Service\Cache\CacheHandler $systemCacheHandler
 * @property Notifications\Service\Notification $notification
 * 
 * @package courses
 * @subpackage model
 */
class Resource
{

    /**
     *
     * @var Utilities\Service\Query\Query 
     */
    protected $query;

    /**
     *
     * @var Zend\Log\Logger
     */
    protected $logger;
        
    /**
     *
     * @var System\Service\Cache\CacheHandler
     */
    protected $systemCacheHandler;

    /**
     *
     * @var Notifications\Service\Notification
     */
    protected $notification;

    /**
     * Set needed properties
     * 
     * @access public
     * @param Utilities\Service\Query\Query $query
     * @param Zend\Log\Logger $logger
     * @param System\Service\Cache\CacheHandler $systemCacheHandler
     * @param Notifications\Service\Notification $notification
     */
    public function __construct($query, $logger, $systemCacheHandler, $notification)
    {
        $this->query = $query;
        $this->logger = $logger;
        $this->systemCacheHandler = $systemCacheHandler;
        $this->notification = $notification;
    }

    /**
     * Save resource
     * 
     * @access public
     * @param Courses\Entity\Resource $resource
     * @param array $data ,default is empty array
     * @param bool $isAdminUser ,default is bool false
     * @param string $userEmail ,default is null
     */
    public function save($resource, $data = array(), $isAdminUser = false, $userEmail = null)
    {
        $notifyAdminFlag = false;
        $editFlag = false;
        if (empty($data)) {
            $editFlag = true;
        }

        if ($isAdminUser === false) {
            $resource->setStatus(Status::STATUS_NOT_APPROVED);
            $notifyAdminFlag = true;
        }

        $this->query->setEntity('Courses\Entity\Resource')->save($resource, $data);
        if (isset($data["fileAdded"])) {
            // save added resources
            foreach ($data["fileAdded"] as $fileKey => $fileValue) {

                $filter = new RenameUpload($fileValue["uploadOptions"]);
                $resource = clone $resource;
                $uploadedFile = $filter->filter($fileValue);
                $resource->setFile($uploadedFile);
                $resource->setName($data["nameAdded"][$fileKey]);
                $this->query->setEntity('Courses\Entity\Resource')->save($resource);
            }
        }
        
        if($notifyAdminFlag === true){
            $this->sendMail($userEmail, $editFlag);
        }
    }

    /**
     * Validate resources
     * 
     * @access public
     * @param Courses\Form\ResourceForm $form
     * @param Courses\Entity\Resource $resource
     * @param array $data ,passed by reference
     * 
     * @return array validation output
     */
    public function validateResources($form, $resource, &$data)
    {
        $formErrors = new FormElementErrors();

        $validationOutput = array();
        // prepare data for validation
        $courseId = $data["course"];
        $validatedFields = array("name", "file");
        // store data needed to reset form
        $originalData = $data;
        $originalFilter = $form->getInputFilter();
        $isValid = true;
        // validate each added resource
        if (isset($data["nameAdded"]) && isset($data["fileAdded"]) &&
                is_array($data["nameAdded"]) && is_array($data["fileAdded"]) &&
                count($data["nameAdded"]) == count($data["fileAdded"])) {
            foreach ($data["nameAdded"] as $nameKey => $nameValue) {
                foreach ($validatedFields as $validatedField) {
                    $validationOutput["addedResources"][$nameKey][$validatedField] = array(
                        "messages" => '',
                        "errorsMarkup" => '',
                        "errorClass" => '',
                        "value" => '',
                    );
                }
                // manipulate data passed to form as if added resource is the original one
                $data["name"] = $nameValue;
                $validationOutput["addedResources"][$nameKey]["name"]["value"] = $nameValue;
                $data["file"] = $data["fileAdded"][$nameKey];
                $data["fileAdded"][$nameKey]["uploadOptions"] = array();
                // update input filter after changing input values
                $form->setInputFilter($resource->getInputFilter($courseId, $nameValue, /* $overrideFilterFlag = */ true, /* $fileUploadOptions = */ $data["fileAdded"][$nameKey]["uploadOptions"]));
                $form->setData($data);
                // validate added resource
                $isValid &= $form->isValid();
                $messages = $form->getMessages();
                foreach ($validatedFields as $validatedField) {
                    // add error messages -if exist-
                    // generate errors markup to be used in display directly
                    if (array_key_exists($validatedField, $messages)) {
                        $validationOutput["addedResources"][$nameKey][$validatedField]["messages"] = $messages[$validatedField];
                        $validationOutput["addedResources"][$nameKey][$validatedField]["errorsMarkup"] = $formErrors->render($form->get($validatedField));
                        $validationOutput["addedResources"][$nameKey][$validatedField]["errorClass"] = "input-error";
                    }
                }
            }
        }
        // reset Form data
        $form->setData($originalData);
        $form->setInputFilter($originalFilter);
        foreach ($validatedFields as $validatedField) {
            $form->get($validatedField)->setMessages(array());
        }
        // validate original resource
        $isValid &= $form->isValid();

        $validationOutput["isValid"] = $isValid;
        return $validationOutput;
    }

    /**
     * Remove resource
     * Remove resource file as well
     * 
     * @access public
     * @param Courses\Entity\Resource $resource
     */
    public function remove($resource)
    {
        $processResult = "true";
        try {
            set_error_handler(function ($errorSeverity, $errorMessage) {
                throw new \Exception($errorMessage, $errorSeverity);
            });
            $file = $resource->getFile()["tmp_name"];
            unlink($file);
            restore_error_handler();
            $this->query->remove($resource);
        } catch (\Exception $e) {
            $this->logger->err($e->getMessage());
            $processResult = "false";
        }

        return $processResult;
    }

    /**
     * Prepare resources for display
     * 
     * @access public
     * @param array $resources
     * 
     * @return array prepared resources
     */
    public function prepareResourcesForDisplay($resources)
    {
        $preparedResources = array();
        foreach ($resources as $resource) {
            if ($resource->getStatus() != Status::STATUS_ACTIVE) {
                continue;
            }
            $preparedResources[$resource->getType()]["files"][] = array(
                "name" => $resource->getName(),
                "id" => $resource->getId(),
            );
            $preparedResources[$resource->getType()]["type"] = $resource->getType();
        }
        return array_values($preparedResources);
    }

    /**
     * Update listed resources
     * 
     * @access public
     * @param array $dataArray
     * @param bool $isAdminUser
     * @param string $userEmail
     */
    public function updateListedResources($dataArray, $isAdminUser, $userEmail)
    {
        if (isset($dataArray['editedName'])) {
            $editedResourceNames = $dataArray['editedName'];
            foreach ($editedResourceNames as $key => $name) {
                $resource = $this->query->findOneBy('Courses\Entity\Resource', array(
                    'id' => $key
                ));
                $resource->setName($name);
                if($isAdminUser === false){
                    $resource->setStatus(Status::STATUS_NOT_APPROVED);
                }
                if (isset($dataArray['editedType'][$key])) {
                    $resource->setType($dataArray['editedType'][$key]);
                    unset($dataArray['editedType'][$key]);
                }
                $this->query->save($resource);
            }
        }

        if (isset($dataArray['editedType'])) {
            $editedResourceType = $dataArray['editedType'];
            foreach ($editedResourceType as $key => $Type) {
                $resource = $this->query->findOneBy('Courses\Entity\Resource', array(
                    'id' => $key
                ));
                $resource->setType($Type);
                if($isAdminUser === false){
                    $resource->setStatus(Status::STATUS_NOT_APPROVED);
                }
                $this->query->save($resource);
            }
        }
        
        if($isAdminUser === false){
            $this->sendMail($userEmail, /*$editFlag =*/ true);
        }
    }

    public function listResourcesForEdit($resources)
    {
        foreach ($resources as $resource) {
            switch ($resource->getType()) {
                case 1:
                    $resource->setType(\Courses\Entity\Resource::TYPE_PRESENTATIONS);
                    break;
                case 2:
                    $resource->setType(\Courses\Entity\Resource::TYPE_ACTIVITIES);
                    break;
                case 3:
                    $resource->setType(\Courses\Entity\Resource::TYPE_EXAMS);
                    break;
            }
        }
        return $resources;
    }

    /**
     * Send mail
     * 
     * @access private
     * @param string $userEmail
     * @param bool $editFlag
     * @throws \Exception From email is not set
     * @throws \Exception To email is not set
     */
    private function sendMail($userEmail, $editFlag)
    {
        $forceFlush = (APPLICATION_ENV == "production" ) ? false : true;
        $cachedSystemData = $this->systemCacheHandler->getCachedSystemData($forceFlush);
        $settings = $cachedSystemData[CacheHandler::SETTINGS_KEY];

        if (array_key_exists(Settings::SYSTEM_EMAIL, $settings)) {
            $from = $settings[Settings::SYSTEM_EMAIL];
        }
        if (array_key_exists(Settings::ADMIN_EMAIL, $settings)) {
            $to = $settings[Settings::ADMIN_EMAIL];
        }

        if (!isset($from)) {
            throw new \Exception("From email is not set");
        }
        if (!isset($to)) {
            throw new \Exception("To email is not set");
        }
        $templateParameters = array(
            "email" => $userEmail,
        );

        if ($editFlag === false) {
            $templateName = MailTempates::NEW_RESOURCE_NOTIFICATION_TEMPLATE;
            $subject = MailSubjects::NEW_RESOURCE_NOTIFICATION_SUBJECT;
        }
        else {
            $templateName = MailTempates::UPDATED_RESOURCE_NOTIFICATION_TEMPLATE;
            $subject = MailSubjects::UPDATED_RESOURCE_NOTIFICATION_SUBJECT;
        }

        $mailArray = array(
            'to' => $to,
            'from' => $from,
            'templateName' => $templateName,
            'templateParameters' => $templateParameters,
            'subject' => $subject,
        );
        $this->notification->notify($mailArray);
    }
}
