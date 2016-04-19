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
use IssueTracker\Entity\IssueComment;

class Comments
{
    /*
     *
     * @var Utilities\Service\Query\Query 
     */

    protected $query;

    /**
     * Set needed properties
     * 
     * 
     * @access public
     * 
     * @param Utilities\Service\Query\Query $query
     */
    public function __construct($query)
    {
        $this->query = $query;
    }

    public function saveComment($data, $issueId, $commentObj = null)
    {
        if ($commentObj == null) {
            $commentObj = new IssueComment();
        }

        $auth = new AuthenticationService();
        $storage = $auth->getIdentity();
        $currentUser = $this->query->findOneBy('Users\Entity\User', array(
            'id' => $storage['id']
        ));

        $issueObj = $this->query->findOneBy('IssueTracker\Entity\Issue', array(
            'id' => $issueId
        ));

        $commentObj->setUser($currentUser);
        $commentObj->setIssue($issueObj);
        $this->query->setEntity('IssueTracker\Entity\IssueComment')->save($commentObj, $data);
    }

    public function getIssueComments($issueId, $aclFlag = false)
    {
        $comments = $this->query->findOneBy('IssueTracker\Entity\Issue', array(
                    'id' => $issueId
                ))->getComments();
        if ($aclFlag) {
            foreach ($comments as $comment) {
                $comment->commentCreator = $this->validateCommentToUser($comment->getId());
            }
        }
        return $comments;
    }

    public function deleteComment($commentId)
    {
        $comment = $this->query->findOneBy('IssueTracker\Entity\IssueComment', array(
            'id' => $commentId
        ));
        $this->query->remove($comment);
    }

    public function getComment($commentId)
    {
        return $this->query->findOneBy('IssueTracker\Entity\IssueComment', array(
                    'id' => $commentId
        ));
    }

    public function validateCommentToUser($commentId)
    {
        $comment = $this->getComment($commentId);
        $auth = new AuthenticationService();
        $storage = $auth->getIdentity();
        if ($comment->getUser()->getId() == $storage['id']) {
            return true;
        }
        return false;
    }

}
