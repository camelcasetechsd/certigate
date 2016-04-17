<?php

namespace IssueTracker\Controller;

use Utilities\Controller\ActionController;
use Zend\View\Model\ViewModel;
use IssueTracker\Form\IssueTrackerForm;
use IssueTracker\Service\IssueCategories;
use IssueTracker\Entity\Issue;
use Users\Entity\Role;
use IssueTracker\Form\CommentForm as Comment;
use IssueTracker\Entity\IssueComment as CommentEntity;
use Zend\Json\Json;

/*
 * 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of IssueController
 *
 * @author ahmedreda
 */
class CommentController extends ActionController
{

    public function removeAction()
    {
        $commentId = $this->params('commentId');
        $issueId = $this->params('issueId');
        $commentsModel = $this->getServiceLocator()->get('IssueTracker\Model\Comments');
        $commentsModel->deleteComment($commentId);
        $url = $this->getEvent()->getRouter()->assemble(array('action' => 'view', 'issueId' => $issueId), array('name' => 'viewIssues'));
        return $this->redirect()->toUrl($url);
    }

    public function editAction()
    {
        $commentId = $this->params('commentId');
        $issueId = $this->params('issueId');
        $commentsModel = $this->getServiceLocator()->get('IssueTracker\Model\Comments');
        $request = $this->getRequest();
        if ($request->isXmlHttpRequest()) {
            $data = $request->getPost()->toArray();
            //validation
            // in case of no response returned so the request fails 
            if ($commentId == $data['id'] && $commentsModel->validateCommentToUser($commentId)) {
                $commentObj = $commentsModel->getComment($commentId);
                $commentsModel->saveComment($data, $issueId, $commentObj);
                // ajax succeeded
                // return what ever you need
                $this->getResponse()->setContent(Json::encode($data));
                return $this->getResponse();
            }
        }
    }

}
