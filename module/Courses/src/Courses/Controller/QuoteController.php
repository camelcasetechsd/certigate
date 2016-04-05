<?php

namespace Courses\Controller;

use Utilities\Controller\ActionController;
use Zend\View\Model\ViewModel;
use Utilities\Service\Status;
use Courses\Form\QuoteFilterForm;
// classes seam not in use, but they are in use via a class path generator
use Courses\Entity\PublicQuote;
use Courses\Entity\PrivateQuote;

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

        $variables['quotes'] = $objectUtilities->prepareForDisplay($quoteModel->getCurrentItems());
        $form = new QuoteFilterForm(/* $name = */ null, /* $options = */ array("quoteModel" => $quoteModel));
        $variables['filterForm'] = $this->getFormView($form->setData($data));
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
        $variables = array();
        $type = ucfirst($this->params('type'));
        $courseModel = $this->getServiceLocator()->get('Courses\Model\Course');
        $courseEventModel = $this->getServiceLocator()->get('Courses\Model\CourseEvent');
        $quoteModel = $this->getServiceLocator()->get('Courses\Model\Quote');
        $publicOrPrivateQuoteModel = $this->getServiceLocator()->get("Courses\Service\QuoteGenerator")->getModel($type);
        $query = $this->getServiceLocator()->get('wrapperQuery')->setEntity("Courses\Entity\\{$type}Quote");

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
        $variables['courses'] = $quoteModel->prepareQuoteForms($courseEventModel->setCourseEventsPrivileges($courseModel->getCurrentItems()), $type, /* $actionUrl = */ $this->getTrainingUrl());
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost()->toArray();
            $quoteEntityClass = "{$type}Quote";
            $quote = new $quoteEntityClass();
            $quoteForm = $quoteModel->getForm($variables['courses'], $type);
            $quoteForm->setInputFilter($quote->getInputFilter());
            $quoteForm->setData($data);
            if ($quoteForm->isValid() && $publicOrPrivateQuoteModel->isValid($quoteForm)) {
                $query->save($quote);
            }
        }
        return new ViewModel($variables);
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
        $query = $this->getServiceLocator()->get('wrapperQuery');
        $quote = $query->find("Courses\Entity\\{$type}Quote", $id);

        $query->remove($quote);

        $this->redirect()->toUrl($this->getTrainingUrl());
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
     * Get training url
     *
     * 
     * @access private
     * @return string training url
     */
    private function getTrainingUrl()
    {
        $type = ucfirst($this->params('type'));
        $filterQuery = $this->getFilterQuery();
        $pageNumber = $this->getRequest()->getQuery('page');
        $options = array('name' => "quoteTraining");
        if(empty($pageNumber)){
            $pageNumber = 1;
        }
        $options["page"] = $pageNumber;
        $url = $this->getEvent()->getRouter()->assemble(/* $params = */ array("type" => $type), $options);
        if(! empty($filterQuery)){
           $url .= "&".$filterQuery; 
        }
        return $url;
    }

}
