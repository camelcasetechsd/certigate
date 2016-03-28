<?php

namespace IssueTracker\Controller;

use Utilities\Controller\ActionController;
use Zend\View\Model\ViewModel;
use IssueTracker\Form\IssueTrackerForm;
use IssueTracker\Form\IssuesCategoriesForm;
use IssueTracker\Service\IssueCategories;

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
class IssueController extends ActionController
{

    public function categoriesAction()
    {
        $variables = array();
        $categoryModel = $this->getServiceLocator()->get('IssueTracker/Model/Categories');
        $data = $categoryModel->getCategories(true);
        $variables['categories'] = $categoryModel->prepareCategoriesDisplay($data);
        return new ViewModel($variables);
    }

    public function newCategoryAction()
    {
        $variables = array();
        $query = $this->getServiceLocator()->get('wrapperQuery');
        $categoryModel = $this->getServiceLocator()->get('IssueTracker\Model\Categories');
        $options['query'] = $query;
        $categoryForm = new IssuesCategoriesForm(null, $options);
        $request = $this->getRequest();
        $categoryObj = new \IssueTracker\Entity\IssueCategory();
        if ($request->isPost()) {
            $data = $request->getPost()->toArray();
            $categoryForm->setInputFilter($categoryObj->getInputFilter($query));
            $categoryForm->setData($data);
            if ($categoryForm->isValid()) {
                $categoryModel->saveCategory($data);
                $url = $this->getEvent()->getRouter()->assemble(array('action' => 'categories'), array('name' => 'listIssuesCategory'));
                $this->redirect()->toUrl($url);
            }
        }
        $variables['categoriesForm'] = $this->getFormView($categoryForm);
        return new ViewModel($variables);
    }

    /**
     * Edit menu
     * 
     * 
     * @access public
     * @uses MenuForm
     * 
     * @return ViewModel
     */
    public function editCategoryAction()
    {
        $variables = array();
        $id = $this->params('issueId');
        $query = $this->getServiceLocator()->get('wrapperQuery');
        $categoryModel = $this->getServiceLocator()->get('IssueTracker\Model\Categories');
        $categoryObj = $query->find('IssueTracker\Entity\IssueCategory', $id);
        $options['query'] = $query;
        $form = new IssuesCategoriesForm(null, $options);
        $form->bind($categoryObj);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost()->toArray();
            $form->setInputFilter($categoryObj->getInputFilter($query));
            $form->setData($data);
            if ($form->isValid()) {
                $categoryModel->saveCategory($data, $categoryObj);
                $url = $this->getEvent()->getRouter()->assemble(array('action' => 'categories'), array('name' => 'listIssuesCategory'));
                $this->redirect()->toUrl($url);
            }
        }
        $variables['categoriesForm'] = $this->getFormView($form);
        return new ViewModel($variables);
    }

    /**
     * Delete menu
     *
     * 
     * @access public
     */
    public function removeCategoryAction()
    {
        $id = $this->params('issueId');
        $categoryModel = $this->getServiceLocator()->get('IssueTracker/Model/Categories');
        $categoryModel->removeCategory(/* old parent */$id, /* new parent */ null);
        $url = $this->getEvent()->getRouter()->assemble(array('action' => 'categories'), array('name' => 'listIssuesCategory'));
        $this->redirect()->toUrl($url);
    }

}
