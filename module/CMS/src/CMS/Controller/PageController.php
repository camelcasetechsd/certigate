<?php

namespace CMS\Controller;

use Utilities\Controller\ActionController;
use Zend\View\Model\ViewModel;
use CMS\Form\PageForm;
use CMS\Entity\Page;
use Utilities\Service\Status;
use CMS\Form\FormViewHelper;
use Zend\Authentication\AuthenticationService;
use Users\Entity\Role;
use Zend\Form\FormInterface;
use CMS\Service\PageTypes;
use Utilities\Form\FormButtons;
use Translation\Service\Locale\Locale as ApplicationLocale;

/**
 * Page Controller
 * 
 * pages entries listing
 * 
 * 
 * 
 * @package cms
 * @subpackage controller
 */
class PageController extends ActionController
{

    /**
     * List pages
     * 
     * 
     * @access public
     * 
     * @return ViewModel
     */
    public function indexAction()
    {
        $variables = array();
        $isAdminUser = $this->isAdminUser();

        $query = $this->getServiceLocator()->get('wrapperQuery')->setEntity('CMS\Entity\Page');
        $objectUtilities = $this->getServiceLocator()->get('objectUtilities');

        $data = $query->findAll(/* $entityName = */'CMS\Entity\Page');
        $variables['pages'] = $objectUtilities->prepareForDisplay($data);
        $variables['isAdminUser'] = $isAdminUser;
        return new ViewModel($variables);
    }

    /**
     * Create new page
     * 
     * 
     * @access public
     * @uses Page
     * @uses PageForm
     * 
     * @return ViewModel
     */
    public function newAction()
    {
        $variables = array();
        $translatorHandler = $this->getServiceLocator()->get('translatorHandler');

        $pageModel = $this->getServiceLocator()->get('CMS\Model\Page');
        $query = $this->getServiceLocator()->get('wrapperQuery')->setEntity('CMS\Entity\Page');
        $pageObj = new Page();

        $options = array();
        $options['query'] = $query;
        $options['translatorHandler'] = $translatorHandler;
        $form = new PageForm(/* $name = */ null, $options);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = array_merge_recursive(
                    $request->getPost()->toArray(), $request->getFiles()->toArray()
            );

            $form->setInputFilter($pageObj->getInputFilter($query));
            $form->setData($data);
            $pageModel->setFormRequiredFields($form, $data, /* $editFlag = */ false);
            if ($form->isValid()) {
                $processedData = $form->getData(FormInterface::VALUES_AS_ARRAY);
                $data["picture"] = $processedData["picture"];
                $pageModel->save($pageObj, $data, /* $editFlag = */ false);

                $url = $this->getEvent()->getRouter()->assemble(array('action' => 'index'), array(
                    'name' => 'cmsPage'));
                $this->redirect()->toUrl($url);
            }
        }

        $formViewHelper = new FormViewHelper();
        $this->setFormViewHelper($formViewHelper);
        $variables['pageForm'] = $this->getFormView($form);
        $variables['pressReleaseType'] = PageTypes::PRESS_RELEASE_TYPE;
        return new ViewModel($variables);
    }

    /**
     * Edit page
     * 
     * 
     * @access public
     * @uses PageForm
     * 
     * @return ViewModel
     */
    public function editAction()
    {
        $variables = array();
        $translatorHandler = $this->getServiceLocator()->get('translatorHandler');
        $id = $this->params('id');
        $pageModel = $this->getServiceLocator()->get('CMS\Model\Page');
        $query = $this->getServiceLocator()->get('wrapperQuery');
        $pageObj = $query->find('CMS\Entity\Page', $id);
        $request = $this->getRequest();
        if (!$request->isPost()) {
            // extract body data to be able to display it in it's natural form
            $pageObj->body = $pageObj->getBody();
            $pageObj->bodyAr = $pageObj->getBodyAr();
        }

        $options = array();
        $options['query'] = $query;
        $options['translatorHandler'] = $translatorHandler;
        $form = new PageForm(/* $name = */ null, $options);
        // removing unpublish button in case of already unpublished page
        if ($pageObj->getStatus() == \Utilities\Service\Status::STATUS_INACTIVE) {
            $form->remove(FormButtons::UNPUBLISH_BUTTON);
            $form->getInputFilter()->remove(FormButtons::UNPUBLISH_BUTTON);
        }
        $form->bind($pageObj);

        if ($request->isPost()) {
            $data = array_merge_recursive(
                    $request->getPost()->toArray(), $request->getFiles()->toArray()
            );
            $form->setInputFilter($pageObj->getInputFilter($query));
            $form->setData($data);

            $pageModel->setFormRequiredFields($form, $data, /* $editFlag = */ true);

            if ($form->isValid()) {
                $pageModel->save($pageObj, $data, /* $editFlag = */ true);

                $url = $this->getEvent()->getRouter()->assemble(array('action' => 'index'), array(
                    'name' => 'cmsPage'));
                $this->redirect()->toUrl($url);
            }
        }

        $formViewHelper = new FormViewHelper();
        $this->setFormViewHelper($formViewHelper);
        $variables['id'] = $id;
        $variables['pageForm'] = $this->getFormView($form);
        $variables['pressReleaseType'] = PageTypes::PRESS_RELEASE_TYPE;
        return new ViewModel($variables);
    }

    /**
     * Delete page
     *
     * 
     * @access public
     */
    public function deleteAction()
    {
        $id = $this->params('id');
        $query = $this->getServiceLocator()->get('wrapperQuery');
        $pageObj = $query->find('CMS\Entity\Page', $id);

        $pageObj->setStatus(Status::STATUS_INACTIVE);

        $query->setEntity('CMS\Entity\Page')->save($pageObj);

        $url = $this->getEvent()->getRouter()->assemble(array('action' => 'index'), array(
            'name' => 'cmsPage'));
        $this->redirect()->toUrl($url);
    }

    /**
     * Activate page
     *
     * 
     * @access public
     */
    public function activateAction()
    {
        $id = $this->params('id');
        $query = $this->getServiceLocator()->get('wrapperQuery');
        $pageObj = $query->find('CMS\Entity\Page', $id);

        $pageObj->setStatus(Status::STATUS_ACTIVE);

        $query->setEntity('CMS\Entity\Page')->save($pageObj);

        $url = $this->getEvent()->getRouter()->assemble(array('action' => 'index'), array(
            'name' => 'cmsPage'));
        $this->redirect()->toUrl($url);
    }

    /**
     * List page history
     *
     * 
     * @access public
     */
    public function historyAction()
    {
        $id = $this->params('id');
        $query = $this->getServiceLocator()->get('wrapperQuery');
        $versionModel = $this->getServiceLocator()->get('Versioning\Model\Version');
        $pageModel = $this->getServiceLocator()->get('CMS\Model\Page');
        $page = $query->find('CMS\Entity\Page', $id);
        $logs = $versionModel->prepareLogs($page);

        $variables = array(
            "logs" => $pageModel->prepareHistory($logs),
            "page" => $page
        );
        return new ViewModel($variables);
    }

    /**
     * View page
     * 
     * 
     * @access public
     * 
     * @return ViewModel
     */
    public function viewAction()
    {
        $staticPagePath = $this->getRequest()->getRequestUri();
        $query = $this->getServiceLocator()->get('wrapperQuery');

        $page = $query->setEntity('CMS\Entity\Page')->entityRepository->getPageByPath($staticPagePath);

        // is inActive page 
        if ($page->getStatus() == \Utilities\Service\Status::STATUS_INACTIVE) {
            $url = $this->getEvent()->getRouter()->assemble(array('action' => 'ResourceNotFound'), array(
                'name' => 'resource_not_found'));
            $this->redirect()->toUrl($url);
        }

        /* @var $applicationLocale ApplicationLocale */
        $applicationLocale = $this->getServiceLocator()->get('applicationLocale');
        switch ($applicationLocale->getCurrentLocale()) {
            case ApplicationLocale::LOCALE_AR_AR:
                $variables = array(
                    "title" => $page->getTitleAr(),
                    "body" => $page->getBodyAr()
                );
                break;
            case ApplicationLocale::LOCALE_EN_US:
            default:
                $variables = array(
                    "title" => $page->getTitle(),
                    "body" => $page->getBody()
                );
                break;
        }

        return new ViewModel($variables);
    }

    /**
     * upload Images
     * 
     * 
     * @access public
     * 
     * @return ViewModel
     */
    public function imgUploadAction()
    {
        $variables = array();
        $pageModel = $this->getServiceLocator()->get('CMS\Model\Page');
        $request = $this->getRequest();
        if ($request->isPost()) {
            $fileData = $request->getFiles()->toArray();
            $source = $pageModel->uploadImage($fileData);
            $variables['imageSource'] = $source;
        }

        return new ViewModel($variables);
    }

    /**
     * browse Images
     * 
     * 
     * @access public
     * 
     * @return ViewModel
     */
    public function browseAction()
    {
        $variables = array();
        $pageModel = $this->getServiceLocator()->get('CMS\Model\Page');
        $images = $pageModel->listImages();
        $variables['images'] = $images;
        return new ViewModel($variables);
    }

}
