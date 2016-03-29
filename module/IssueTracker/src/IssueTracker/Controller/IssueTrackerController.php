<?php

namespace IssueTracker\Controller;

use Utilities\Controller\ActionController;
use Zend\View\Model\ViewModel;
use IssueTracker\Form\IssueTrackerForm;
use IssueTracker\Service\IssueCategories;
use IssueTracker\Entity\Issue;
use Zend\Authentication\AuthenticationService;
use Users\Entity\Role;

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
        $variables['issues'] = $issuesModel->prepareIssuesToView($issuesModel->listIssues());
        $auth = new AuthenticationService();
        $storage = $auth->getIdentity();
        if (in_array(Role::ADMIN_ROLE, $storage['roles'])) {
            $variables['isAdmin'] = true;
        }
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
