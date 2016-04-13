<?php

namespace Utilities\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Form\FormInterface;
use Zend\Form\View\Helper\Form;
use Zend\Authentication\AuthenticationService;
use Users\Entity\Role;

/**
 * Action Controller
 * 
 * Being extended by all controllers, 
 * It provides helpers for all controllers
 * And control how all controllers behave
 * 
 * @property Utilities\Service\View\FormView $formViewService
 * @property array $storage
 * 
 * @package utilities
 * @subpackage controller
 */
class ActionController extends AbstractActionController
{

    /**
     *
     * @var Utilities\Service\View\FormView 
     */
    protected $formViewService;

    /**
     *
     * @var array 
     */
    protected $storage;

    /**
     * Set needed properties
     * 
     * @access public
     */
    public function __construct()
    {
        $auth = new AuthenticationService();
        $storage = $auth->getIdentity();
        if ($auth->hasIdentity()) {
            $this->storage = $storage;
        }
    }

    /**
     * Get form HTML content
     * 
     * 
     * @access public
     * 
     * @param FormInterface $form
     * @return string form HTML content
     */
    public function getFormView(FormInterface $form)
    {
        $this->setFormViewService();
        return $this->formViewService->getFormView($form);
    }

    /**
     * Set form view helper
     * 
     * 
     * @access public
     * 
     * @param Form $formViewHelper
     */
    public function setFormViewHelper(Form $formViewHelper)
    {
        $this->setFormViewService();
        $this->formViewService->setFormViewHelper($formViewHelper);
    }

    /**
     * Set form view service
     * 
     * 
     * @access public
     */
    public function setFormViewService()
    {
        if (is_null($this->formViewService)) {
            $this->formViewService = $this->getServiceLocator()->get('Utilities\Service\View\FormView');
        }
    }

    /**
     * Get form view helper
     * 
     * 
     * @access public
     * @uses Utilities\Form\FormViewHelper
     * 
     * @return Form form view helper
     */
    public function getFormViewHelper()
    {
        $this->setFormViewService();
        return $this->formViewService->getFormViewHelper();
    }

    /**
     * Get pdf view
     * 
     * 
     * @access public
     * @param PdfModel $pdfModel
     * 
     * @return HttpResponse pdf response
     */
    public function getPdfView($pdfModel)
    {
        $viewPdfRenderer = $this->getServiceLocator()->get('ViewPdfRenderer');
        $mustacheRenderer = $this->getServiceLocator()->get('Mustache\View\Renderer');
        $renderedPdf = $viewPdfRenderer->setHtmlRenderer($mustacheRenderer)->render($pdfModel);
        $this->getResponse()->getHeaders()->addHeaderLine('content-type', 'application/pdf');
        $fileName = $pdfModel->getOption('filename');
        if (isset($fileName)) {
            if (substr($fileName, -4) != '.pdf') {
                $fileName .= '.pdf';
            }

            $this->getResponse()->getHeaders()->addHeaderLine(
                    'Content-Disposition', 'attachment; filename=' . $fileName);
        }

        return $this->getResponse()->setContent($renderedPdf);
    }

    /**
     * Has current logged in user admin permission
     * 
     * @access public
     * @uses AuthenticationService
     * @return bool is admin user
     */
    public function isAdminUser()
    {
        $isAdminUser = false;
        if (!empty($this->storage)) {
            if (in_array(Role::ADMIN_ROLE, $this->storage['roles'])) {
                $isAdminUser = true;
            }
        }
        return $isAdminUser;
    }

    /**
     * Get filter query
     * 
     * @access public
     * @return string query string without pagination parameter
     */
    public function getFilterQuery()
    {
        return preg_replace('/page=[\d]+(&)?/i', '', $this->getRequest()->getUri()->getQuery());
    }

}
