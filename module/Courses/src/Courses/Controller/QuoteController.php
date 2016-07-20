<?php

namespace Courses\Controller;

use Utilities\Controller\ActionController;
use Zend\View\Model\ViewModel;
use Utilities\Service\Status;
use Courses\Form\QuoteFilterForm;

/**
 * Quote Controller
 * 
 * public and private quotes entries listing
 * 
 * 
 * 
 * @package courses
 * @subpackage controller
 */
class QuoteController extends ActionController
{

    /**
     * List quotes events
     * 
     * 
     * @access public
     * 
     * @return ViewModel
     */
    public function indexAction()
    {
        $isAdminUser = $this->isAdminUser();
        $userId = $this->storage['id'];
        $request = $this->getRequest();
        $data = $request->getQuery()->toArray();
        $formSmasher = $this->getServiceLocator()->get('formSmasher');
        $pageNumber = $request->getQuery('page');
        $quoteModel = $this->getServiceLocator()->get('Courses\Model\Quote');
        $objectUtilities = $this->getServiceLocator()->get('objectUtilities');

        $variables = array();
        $quoteModel->filterQuotes($isAdminUser, $userId, $data);
        $quoteModel->setPage($pageNumber);
        $pageNumbers = $quoteModel->getPagesRange($pageNumber);
        $nextPageNumber = $quoteModel->getNextPageNumber($pageNumber);
        $previousPageNumber = $quoteModel->getPreviousPageNumber($pageNumber);
        $variables['pageNumbers'] = $pageNumbers;
        $variables['hasPages'] = ( count($pageNumbers) > 0 ) ? true : false;
        $variables['nextPageNumber'] = $nextPageNumber;
        $variables['previousPageNumber'] = $previousPageNumber;
        $variables['isAdminUser'] = $isAdminUser;
        $variables['type'] = strtolower($quoteModel->getCurrentType());

        $variables['quotes'] = $objectUtilities->prepareForDisplay($quoteModel->getCurrentItems());
        $form = new QuoteFilterForm(/* $name = */ null, /* $options = */ array("quoteModel" => $quoteModel));
        $variables = $formSmasher->prepareFormForDisplay($form->setData($data), /* elements containers */ $variables);
//        var_dump($variables);exit;
//         $variables['filterForm'] = $this->getFormView($form);
        $variables['filterQuery'] = $this->getFilterQuery();
        return new ViewModel($variables);
    }

    /**
     * Courses private or public training list
     * 
     * 
     * @access public
     * 
     * @return ViewModel
     */
    public function trainingAction()
    {
        $isAdminUser = $this->isAdminUser();
        $variables = array();
        $type = ucfirst($this->params('type'));
        $courseModel = $this->getServiceLocator()->get('Courses\Model\Course');
        $courseEventModel = $this->getServiceLocator()->get('Courses\Model\CourseEvent');
        $quoteModel = $this->getServiceLocator()->get('Courses\Model\Quote');
        $publicOrPrivateQuoteModel = $this->getServiceLocator()->get("Courses\Service\QuoteGenerator")->getModel($type);

        $pageNumber = $this->getRequest()->getQuery('page');
        $courseModel->filterCourses(/* $criteria = */ array("status" => Status::STATUS_ACTIVE));
        $courseModel->setPage($pageNumber);
        $pageNumbers = $courseModel->getPagesRange($pageNumber);
        $nextPageNumber = $courseModel->getNextPageNumber($pageNumber);
        $previousPageNumber = $courseModel->getPreviousPageNumber($pageNumber);
        $variables['pageNumbers'] = $pageNumbers;
        $variables['hasPages'] = ( count($pageNumbers) > 0 ) ? true : false;
        $variables['nextPageNumber'] = $nextPageNumber;
        $variables['previousPageNumber'] = $previousPageNumber;
        $variables['type'] = $type;
        $courses = $quoteModel->prepareReservationForms(/* $courses = */ $courseModel->getCurrentItems(), $type, /* $userId = */ $this->storage['id'], /* $actionUrl = */ $this->getRedirectUrl());
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost()->toArray();
            $quoteEntityClass = $quoteModel->getQuoteEntityClass($type);
            $quote = new $quoteEntityClass();
            $quoteForm = $quoteModel->getReservationForm($courses, $type, $data);
            $quoteForm->setData($data);
            if ($publicOrPrivateQuoteModel->isReservationValid($quoteForm) && $quoteForm->isValid()) {
                $quoteModel->save($quote, $quoteForm, $data, $type, /* $editFlag = */ false, $isAdminUser, /* $userEmail = */ $this->storage['email']);
                $url = $this->getEvent()->getRouter()->assemble(/* $params = */ array(), /* $options = */ array("name" => "quote", "query" => array("type" => $type)));
                $this->redirect()->toUrl($url);
            }
            $quoteModel->setForm($quoteForm, $courses, $type, $data);
        }
        $variables['courses'] = $courseEventModel->setCourseEventsPrivileges($courses);
        return new ViewModel($variables);
    }

    /**
     * Process quote
     * 
     * 
     * @access public
     * @uses CourseEventForm
     * 
     * @return ViewModel
     */
    public function processAction()
    {
        $variables = array();
        $type = ucfirst($this->params('type'));
        $id = $this->params('id');
        $isAdminUser = $this->isAdminUser();

        $quoteModel = $this->getServiceLocator()->get('Courses\Model\Quote');
        $form = $quoteModel->getQuoteForm($type, $id, /* $userId = */ $this->storage["id"]);
        $quote = $form->getObject();
        $variables["course"] = $quoteModel->prepareQuoteCourseForDisplay($quote, $type);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = array_merge_recursive(
                    $request->getPost()->toArray(), $request->getFiles()->toArray()
            );
            $form->setInputFilter($quote->getInputFilter($quote->getStatus()));
            $form->setData($data);

            if ($quoteModel->isQuoteFormValid($form, $quote, $data, $type, $isAdminUser)) {
                $quoteModel->save($quote, $form, $data, $type, /* $editFlag = */ true, $isAdminUser, /* $userEmail = */ $this->storage['email']);
                $this->redirect()->toUrl($this->getRedirectUrl(/* $routeName = */ "quote"));
            }
        }
        $quoteModel->prepareQuoteForDisplay($quote, $type);
        $variables["isAdminUser"] = $isAdminUser;
        $variables["quote"] = $quote;
        $variables['form'] = $this->getFormView($form);
        $variables['type'] = strtolower($type);
        $variables['id'] = $id;
        return new ViewModel($variables);
    }

    /**
     * Download quote
     *
     * 
     * @access public
     */
    public function downloadAction()
    {
        $id = $this->params('id');
        $type = ucfirst($this->params('type'));
        $query = $this->getServiceLocator()->get('wrapperQuery');
        $fileUtilities = $this->getServiceLocator()->get('fileUtilities');
        $quoteModel = $this->getServiceLocator()->get('Courses\Model\Quote');

        $quote = $query->find($quoteModel->getQuoteEntityClass($type), /* $criteria = */ $id);
        $file = $quote->getWireTransfer()["tmp_name"];
        return $fileUtilities->getFileResponse($file);
    }

    /**
     * Delete quote
     *
     * 
     * @access public
     */
    public function deleteAction()
    {
        $id = $this->params('id');
        $type = ucfirst($this->params('type'));
        $quoteModel = $this->getServiceLocator()->get('Courses\Model\Quote');
        $quoteModel->delete($type, $id);

        $this->redirect()->toUrl($this->getRedirectUrl(/* $routeName = */ "quote"));
    }

    /**
     * Cleanup quote
     *
     * 
     * @access public
     */
    public function cleanupAction()
    {
        $quoteModel = $this->getServiceLocator()->get('Courses\Model\Quote');
        $quoteModel->cleanup();
    }

    /**
     * Get redirect url
     *
     * 
     * @access private
     * @param string $routeName ,default is "quoteTraining"
     * @return string redirect url
     */
    private function getRedirectUrl($routeName = "quoteTraining")
    {
        $type = $this->params('type');
        $filterQuery = $this->getFilterQuery();
        $pageNumber = $this->getRequest()->getQuery('page');
        $options = array('name' => $routeName);
        if (empty($pageNumber)) {
            $pageNumber = 1;
        }
        $options["query"] = array("page" => $pageNumber, "type" => ucfirst($type));
        $url = $this->getEvent()->getRouter()->assemble(/* $params = */ array("type" => $type), $options);
        if (!empty($filterQuery)) {
            $url .= "&" . $filterQuery;
        }
        return $url;
    }

}
