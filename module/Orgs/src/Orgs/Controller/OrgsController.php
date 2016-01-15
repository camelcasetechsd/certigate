<?php

namespace Orgs\Controller;

use Zend\View\Model\ViewModel;
use Utilities\Controller\ActionController;
use Orgs\Form\OrgForm;
use Orgs\Entity\Org as OrgEntity;
use Orgs\Model\Org as OrgModel;

/**
 * Atps Controller
 * 
 * Atps entries listing
 * 
 * 
 * 
 * @package directories
 * @subpackage controller
 */
class OrgsController extends ActionController
{

    /**
     * List ATCs
     * 
     * 
     * @access public
     * 
     * @return ViewModel
     */
    public function atcsAction()
    {

        return new ViewModel();
    }

    /**
     * List ATCs
     * 
     * 
     * @access public
     * 
     * @return ViewModel
     */
    public function atpsAction()
    {

        return new ViewModel();
    }

    /**
     * more details of an ATC
     * 
     * 
     * @access public
     * 
     * @return ViewModel
     */
    public function moreAction()
    {

        return new ViewModel();
    }

    /**
     * create new ATC
     * 
     * 
     * @access public
     * 
     * @return ViewModel
     */
    public function newAction()
    {
        $variables = array();
        $query = $this->getServiceLocator()->get('wrapperQuery')->setEntity('Users\Entity\User');
        $orgModel = $this->getServiceLocator()->get('Orgs\Model\Org');
        $orgObj = new OrgEntity();
        $options = array();
        $options['query'] = $query;

        $form = new OrgForm(/* $name = */ null, $options);

        $request = $this->getRequest();
        if ($request->isPost()) {

            // Make certain to merge the files info!
            $fileData = $request->getFiles()->toArray();
//
            $data = array_merge_recursive(
                    $request->getPost()->toArray(), $fileData
            );
//
            $form->setInputFilter($orgObj->getInputFilter());
            $form->setData($data);

            if (!empty($orgModel->checkOrgExistance($data['commercialName']))) {
                $form->get('commercialName')->setMessages(array("Organization already Exists"));
            }


            if ($form->isValid()) {

                $orgModel->saveOrganization($data);
                
                




                // redirecting
                
                if ($data['type'] == 1) {
                    $url = $this->getEvent()->getRouter()->assemble(array('action' => 'atps'), array('name' => 'list_atc_orgs'));
                }
                else if ($data['type'] == 2) {
                    $url = $this->getEvent()->getRouter()->assemble(array('action' => 'atcs'), array('name' => 'list_atp_orgs'));
                }

                $this->redirect()->toUrl($url);
            }
            else {

                var_dump("invalid");
                exit;
            }
        }

        $variables['orgForm'] = $this->getFormView($form);
        return new ViewModel($variables);
    }

    /**
     * more details of an ATC
     * 
     * 
     * @access public
     * 
     * @return ViewModel
     */
    public function editAction()
    {

        return new ViewModel();
    }

}
