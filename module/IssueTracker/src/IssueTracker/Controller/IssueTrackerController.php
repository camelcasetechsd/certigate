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
class IssueTrackerController extends ActionController
{

    public function indexAction()
    {
        $variables = array();
        $issuesModel = $this->getServiceLocator()->get('IssueTracker\Model\Issues');
        $variables['issues'] = $issuesModel->getCurrentItems();
        $variables['isAdmin'] = $issuesModel->validateUser();

        $issuesModel->filterIssues();
        $pageNumber = $this->getRequest()->getQuery('page');
        $issuesModel->setPage($pageNumber);

        $pageNumbers = $issuesModel->getPagesRange($pageNumber);
        $nextPageNumber = $issuesModel->getNextPageNumber($pageNumber);
        $previousPageNumber = $issuesModel->getPreviousPageNumber($pageNumber);


        $variables['pageNumbers'] = $pageNumbers;
        $variables['hasPages'] = ( count($pageNumbers) > 0 ) ? true : false;
        $variables['nextPageNumber'] = $nextPageNumber;
        $variables['previousPageNumber'] = $previousPageNumber;
        return new ViewModel($variables);
    }

    public function newAction()
    {
        $variables = array();
        $query = $this->getServiceLocator()->get('wrapperQuery');
        $issuesModel = $this->getServiceLocator()->get('IssueTracker\Model\Issues');
        $options['query'] = $query;
        $issueTrackerForm = new IssueTrackerForm(null, $options);
        $request = $this->getRequest();
        $issueObj = new Issue();
        if ($request->isPost()) {
            $fileData = $request->getFiles()->toArray();
            $data = array_merge_recursive(
                    $request->getPost()->toArray(), $fileData
            );
            $issueTrackerForm->setInputFilter($issueObj->getInputFilter($query));
            $issueTrackerForm->setData($data);
            if ($issueTrackerForm->isValid()) {
                $issuesModel->saveIssue($data);
                $url = $this->getEvent()->getRouter()->assemble(array('action' => 'index'), array('name' => 'issues'));
                return $this->redirect()->toUrl($url);
            }
        }
        $variables['trackerForm'] = $this->getFormView($issueTrackerForm);
        return new ViewModel($variables);
    }

    public function viewAction()
    {
        $variables = array();
        $issueId = $this->params('issueId');
        $query = $this->getServiceLocator()->get('wrapperQuery');
        $commentsModel = $this->getServiceLocator()->get('IssueTracker\Model\Comments');
        $issuesModel = $this->getServiceLocator()->get('IssueTracker\Model\Issues');
        $commentForm = new Comment();
        $commentObj = new CommentEntity();
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost()->toArray();
            $commentForm->setInputFilter($commentObj->getInputFilter($query));
            $commentForm->setData($data);
            if ($commentForm->isValid()) {
                $commentsModel->saveComment($data, $issueId, null);
                $url = $this->getEvent()->getRouter()->assemble(array('action' => 'view', 'issueId' => $issueId), array('name' => 'viewIssues'));
                return $this->redirect()->toUrl($url);
            }
        }
        $variables['previousComments'] = $commentsModel->getIssueComments($issueId, true);
        $variables['commentForm'] = $this->getFormView($commentForm);
        $variables['issue'] = $issuesModel->prepareIssuesToView(array($issuesModel->getIssue($issueId)));
        $variables['isAdmin'] = $issuesModel->validateUser();
        $variables['currentUser'] = $issuesModel->getCurrentUser();

        return new ViewModel($variables);
    }

    public function closeAction()
    {
        $issueId = $this->params('issueId');
        $issuesModel = $this->getServiceLocator()->get('IssueTracker\Model\Issues');
        $issuesModel->closeIssue($issueId);
        $url = $this->getEvent()->getRouter()->assemble(array('action' => 'index'), array('name' => 'issues'));
        return $this->redirect()->toUrl($url);
    }

    public function reopenAction()
    {
        $issueId = $this->params('issueId');
        $issuesModel = $this->getServiceLocator()->get('IssueTracker\Model\Issues');
        $issuesModel->reopenIssue($issueId);
        $url = $this->getEvent()->getRouter()->assemble(array('action' => 'index'), array('name' => 'issues'));
        return $this->redirect()->toUrl($url);
    }

    public function deleteAction()
    {
        $issueId = $this->params('issueId');
        $issuesModel = $this->getServiceLocator()->get('IssueTracker\Model\Issues');
        $issuesModel->deleteIssue($issueId);
        $url = $this->getEvent()->getRouter()->assemble(array('action' => 'index'), array('name' => 'issues'));
        return $this->redirect()->toUrl($url);
    }

}
