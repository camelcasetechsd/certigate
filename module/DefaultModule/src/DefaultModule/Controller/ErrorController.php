<?php

namespace DefaultModule\Controller;

use Utilities\Controller\ActionController;
use Zend\View\Model\ViewModel;
use DefaultModule\Service\ErrorMessages;

/**
 * Error Controller
 * 
 * 
 * 
 * @package defaultModule
 * @subpackage controller
 */
class ErrorController extends ActionController
{

    /**
     * No Access Action
     * 
     * 
     * @access public
     * @return ViewModel
     */
    public function noaccessAction()
    {
        return new ViewModel();
    }

    /**
     * No Agreement Action
     * 
     * @access public
     * @return ViewModel
     */
    public function noAgreementAction()
    {
        $role = $this->params('role');
        $id = $this->params('id');
        $variables = array(
            "role" => $role,
            "id" => $id,
        );
        return new ViewModel($variables);
    }

    public function resourceNotFoundAction()
    {
        $variables = array();
        $variables["message"] = ErrorMessages::getErrorMessage(/*$messageKey =*/ $this->params('message'));
        return new ViewModel($variables);
    }

    public function noOrganizationUsersAction()
    {
        return new ViewModel();
    }

}
