<?php

namespace Organizations\Controller;

use Zend\View\Model\ViewModel;
use Utilities\Controller\ActionController;
use Organizations\Form\OrgForm as OrgForm;
use Organizations\Entity\Organization as OrgEntity;
use Doctrine\Common\Collections\Criteria;
use Utilities\Service\Time;
use Zend\Json\Json;
use Zend\Authentication\AuthenticationService;
use Users\Entity\Role;
use Utilities\Service\Status;
use Organizations\Service\Messages;

/**
 * Atps Controller
 * 
 * Atps entries listing
 * 
 *
 * 
 * @package organizations
 * @subpackage controller
 */
class OrganizationsController extends ActionController
{

    /**
     * List organizations
     * 
     * 
     * @access public
     * 
     * @return ViewModel
     */
    public function indexAction()
    {
        $variables = array();
        $query = $this->getServiceLocator()->get('wrapperQuery')->setEntity('Organizations\Entity\Organization');
        $organizationModel = $this->getServiceLocator()->get('Organizations\Model\Organization');
        $objectUtilities = $this->getServiceLocator()->get('objectUtilities');

        $criteria = Criteria::create();
        $expr = Criteria::expr();
        $criteria->andWhere($expr->in("status", array(Status::STATUS_INACTIVE, Status::STATUS_ACTIVE, Status::STATUS_NOT_APPROVED)));

        $data = $query->filter(/* $entityName = */'Organizations\Entity\Organization', $criteria);
        $variables['organizations'] = $organizationModel->prepareForDisplay($objectUtilities->prepareForDisplay($data));
        return new ViewModel($variables);
    }

    public function typeAction()
    {
        $query = $this->getServiceLocator()->get('wrapperQuery');
        $rolesArray = array(Role::TEST_CENTER_ADMIN_ROLE, Role::TRAINING_MANAGER_ROLE);
        $validationResult = $this->getServiceLocator()->get('aclValidator')->validateOrganizationAccessControl(/* $response = */$this->getResponse(), $rolesArray, /* $organization = */ null, /* $atLeastOneRoleFlag = */ true);
        if ($validationResult["isValid"] === false && !empty($validationResult["redirectUrl"])) {
            return $this->redirect()->toUrl($validationResult["redirectUrl"]);
        }
        $variables = array();
        $options['query'] = $query;
        $form = new \Organizations\Form\TypeForm(/* $name = */ null, $options);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = array_merge_recursive(
                    $request->getPost()->toArray()
            );
            $form->setData($data);
            if ($form->isValid()) {
                $url = $this->getEvent()->getRouter()->assemble(array('action' => 'new'), array('name' => 'new_org'));
                return $this->redirect()->toUrl($url . $data['type']);
            }
        }
        $variables['TypeForm'] = $this->getFormView($form);
        return new ViewModel($variables);
    }

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
        $organizationModel = $this->getServiceLocator()->get('Organizations\Model\Organization');
        $organizationUserModel = $this->getServiceLocator()->get('Organizations\Model\OrganizationUser');

        $variables['atcs'] = $organizationModel->listOrganizations(OrgEntity::TYPE_ATC);

        foreach ($variables['atcs'] as $org) {
            $variables['orgUser'] = $organizationUserModel->isOrganizationUser(null, $org);
            $variables['isAdmin'] = $organizationUserModel->isAdmin();
            $org->atcLicenseExpiration = $org->getAtcLicenseExpiration()->format(Time::DATE_FORMAT);
        }
        $variables['atcTypeId'] = OrgEntity::TYPE_ATC;
        return new ViewModel($variables);
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
        $organizationModel = $this->getServiceLocator()->get('Organizations\Model\Organization');
        $organizationUserModel = $this->getServiceLocator()->get('Organizations\Model\OrganizationUser');
        $variables['atps'] = $organizationModel->listOrganizations(OrgEntity::TYPE_ATP);
        foreach ($variables['atps'] as $org) {
            $variables['orgUser'] = $organizationUserModel->isOrganizationUser(null, $org);
            $variables['isAdmin'] = $organizationUserModel->isAdmin();
            $org->atpLicenseExpiration = $org->getAtpLicenseExpiration()->format(Time::DATE_FORMAT);
        }
        $variables['atpTypeId'] = OrgEntity::TYPE_ATP;
        return new ViewModel($variables);
    }

    /**
     * List distributors
     * 
     * 
     * @access public
     * 
     * @return ViewModel
     */
    public function distributorsAction()
    {
        $organizationModel = $this->getServiceLocator()->get('Organizations\Model\Organization');
        $organizationUserModel = $this->getServiceLocator()->get('Organizations\Model\OrganizationUser');
        $organizations = $organizationModel->listOrganizations(OrgEntity::TYPE_DISTRIBUTOR);
        foreach ($organizations as $org) {
            $variables['orgUser'] = $organizationUserModel->isOrganizationUser($this, $org);
            $variables['isAdmin'] = $organizationUserModel->isAdmin();
        }
        $variables['distributors'] = $organizations;
        $variables['distributorsTypeId'] = OrgEntity::TYPE_DISTRIBUTOR;
        return new ViewModel($variables);
    }

    /**
     * List resellers
     * 
     * 
     * @access public
     * 
     * @return ViewModel
     */
    public function resellersAction()
    {
        $organizationModel = $this->getServiceLocator()->get('Organizations\Model\Organization');
        $organizationUserModel = $this->getServiceLocator()->get('Organizations\Model\OrganizationUser');
        $organizations = $organizationModel->listOrganizations(OrgEntity::TYPE_RESELLER);
        foreach ($organizations as $org) {
            $variables['orgUser'] = $organizationUserModel->isOrganizationUser($this, $org);
            $variables['isAdmin'] = $organizationUserModel->isAdmin();
        }
        $variables['resellers'] = $organizations;
        $variables['resellersTypeId'] = OrgEntity::TYPE_RESELLER;
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
    public function moreAction()
    {

        $organizationModel = $this->getServiceLocator()->get('Organizations\Model\Organization');
        $id = $this->getEvent()->getRouteMatch()->getParam('id');
        $variables['userData'] = $organizationModel->getOrganizationby('id', $id)[0];


        $variables['userData']->CRExpiration = $variables['userData']->getCRExpiration()->format('Y-m-d');
        // skip atc expiration if atp
        if ($variables['userData']->atcLicenseExpiration != null) {
            $variables['userData']->atcLicenseExpiration = $variables['userData']->getAtcLicenseExpiration()->format('Y-m-d');
        }
        // skip atp expiration if atc
        if ($variables['userData']->atpLicenseExpiration != null) {
            $variables['userData']->atpLicenseExpiration = $variables['userData']->getAtpLicenseExpiration()->format('Y-m-d');
        }
        $variables = $organizationModel->prepareStatics($variables);
        return new ViewModel($variables);
    }

    /**
     * renewing attchments  
     */
    public function renewAction()
    {
        $variables = array();
        $organizationId = $this->params('organizationId');
        $metaId = $this->params('metaId');
        $OrganizationModel = $this->getServiceLocator()->get('Organizations\Model\Organization');

        if ($OrganizationModel->canBeRenewed($this, $organizationId, $metaId)) {
            $customizedForm = $this->getFormView($OrganizationModel->getCustomizedRenewalForm($this, $organizationId, $metaId));

            $request = $this->getRequest();
            if ($request->isPost()) {
                $fileData = $request->getFiles()->toArray();
                $data = array_merge_recursive(
                        $request->getPost()->toArray(), $fileData
                );
                $OrganizationModel->renewOrganization($this, $organizationId, $data);
            }
            $variables['renewForm'] = $customizedForm;
        }
        else {
            $variables['messages'] = Messages::NO_RENEWAL_TYPE;
            $variables['type'] = 'warning'; // TODO : change it after merging new layout messages
        }

        return new ViewModel($variables);
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
        $cleanQuery = $this->getServiceLocator()->get('wrapperQuery');
        $applicationLocale = $this->getServiceLocator()->get('applicationLocale');
        $query = $cleanQuery->setEntity('Users\Entity\User');
        $orgsQuery = $cleanQuery->setEntity('Organizations\Entity\Organization');
        $orgModel = $this->getServiceLocator()->get('Organizations\Model\Organization');

        /**
         *  chicking url minpulation by sending object of the action
         *  to analyize each parameter
         */
        $orgModel->validateUrlParamters($this);

        $auth = new AuthenticationService();
        $storage = $auth->getIdentity();

        $isAdminUser = false;
        $creatorId = false;
        $userEmail = false;
        if ($auth->hasIdentity()) {
            if (in_array(Role::ADMIN_ROLE, $storage['roles'])) {
                $isAdminUser = true;
            }
            $creatorId = $storage['id'];
            $userEmail = $storage['email'];
        }

        $orgObj = new OrgEntity();
        $options = array();
        $rolesArray = $orgModel->getRequiredRoles($orgModel->getOrganizationTypes($this, null));
        $validationResult = $this->getServiceLocator()->get('aclValidator')->validateOrganizationAccessControl(/* $response = */$this->getResponse(), $rolesArray);
        if ($validationResult["isValid"] === false && !empty($validationResult["redirectUrl"])) {
            return $this->redirect()->toUrl($validationResult["redirectUrl"]);
        }

// //     TODO: Delaying Save State business
//        $savedState = $orgModel->hasSavedState($orgType, $creatorId);
//        if ($savedState != null) {
//            $url = $this->getEvent()->getRouter()->assemble(array('action' => 'edit', 'id' => $savedState), array('name' => 'edit_org'));
//            $this->redirect()->toUrl($url . '?organization=' . $orgType);
//        }

        $options['query'] = $query;
        $options['staticLangs'] = OrgEntity::getStaticLangs();
        $options['staticOss'] = OrgEntity::getOSs();
        $options['staticOfficeVersions'] = OrgEntity::getOfficeVersions();
        $options['applicationLocale'] = $applicationLocale;
        $customizedForm = $orgModel->customizeOrgForm($rolesArray, $options, $this);

        $request = $this->getRequest();
        if ($request->isPost()) {
            // Make certain to merge the files info!
            $fileData = $request->getFiles()->toArray();
            $data = array_merge_recursive(
                    $request->getPost()->toArray(), $fileData
            );

            $customizedForm->setData($data);
            $data['creatorId'] = $creatorId;

            if ($customizedForm->isValid()) {
                $orgModel->saveOrganization($this, $data, /* $orgObj = */ null, /* $oldStatus = */ null, /*$oldLongitude =*/ null, /*$oldLatitude =*/ null, $creatorId, $userEmail, $isAdminUser);
            }
        }

        $variables['orgForm'] = $this->getFormView($customizedForm);
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
        $variables = array();
        $id = $this->params('id');
        $query = $this->getServiceLocator()->get('wrapperQuery');
        $applicationLocale = $this->getServiceLocator()->get('applicationLocale');
        $orgsQuery = $this->getServiceLocator()->get('wrapperQuery')->setEntity('Organizations\Entity\Organization');
        $orgObj = $query->find('Organizations\Entity\Organization', $id);
        $orgModel = $this->getServiceLocator()->get('Organizations\Model\Organization');

        $rolesArray = $orgModel->getRequiredRoles($orgModel->getOrganizationTypes(null, $orgObj));
        $validationResult = $this->getServiceLocator()->get('aclValidator')->validateOrganizationAccessControl(/* $response = */$this->getResponse(), $rolesArray, $orgObj);
        if ($validationResult["isValid"] === false && !empty($validationResult["redirectUrl"])) {
            return $this->redirect()->toUrl($validationResult["redirectUrl"]);
        }
//        // for checking on attachments 
        $crAttachment = $orgObj->CRAttachment;
        $oldStatus = $orgObj->getStatus();
        $oldLongitude = $orgObj->getLongitude();
        $oldLatitude = $orgObj->getLatitude();
//
        $isAdminUser = $this->isAdminUser();
        // allow access for admins for all users
        // restrict access for current user only for non-admin users

        $options = array();
        $options['query'] = $query;
        $options['staticLangs'] = OrgEntity::getStaticLangs();
        $options['staticOss'] = OrgEntity::getOSs();
        $options['staticOfficeVersions'] = OrgEntity::getOfficeVersions();
        $options['applicationLocale'] = $applicationLocale;
        
        $customizedForm = $orgModel->customizeOrgEditForm($options, $this, $id);
        $customizedForm->bind($orgObj);

        $request = $this->getRequest();
        if ($request->isPost()) {

//            Make certain to merge the files info!
//            $fileData = $request->getFiles()->toArray();

            $fileData = $request->getFiles()->toArray();
            $data = array_merge_recursive(
                    $request->getPost()->toArray(), $fileData
            );

            $customizedForm->setData($data);

            // file not updated
            if (isset($fileData['CRAttachment']['name']) && empty($fileData['CRAttachment']['name'])) {
                // Change required flag to false for any previously uploaded files
                $customizedForm->getInputFilter()->get('CRAttachment')->setRequired(false);
            }

            if ($customizedForm->isValid()) {
                $orgModel->saveOrganization($this, $data, $orgObj, $oldStatus, $oldLongitude, $oldLatitude, /* $creatorId = */ null, /* $userEmail = */ null, $isAdminUser);
            }
        }
        $variables['CRAttachment'] = $crAttachment;
        $variables['organizationForm'] = $this->getFormView($customizedForm);

        $organizationArray = array($orgObj);
        $versionModel = $this->getServiceLocator()->get('Versioning\Model\Version');
        $organizationLogs = $versionModel->getLogEntriesPerEntities(/* $entities = */ $organizationArray, /* $objectIds = */ array(), /* $objectClass = */ null, /* $status = */ Status::STATUS_NOT_APPROVED);

        $hasPendingChanges = (count($organizationLogs) > 0) ? true : false;
        $pendingUrl = $this->getEvent()->getRouter()->assemble(array('id' => $id), array('name' => 'organizationsPending'));
        $variables['messages'] = $versionModel->getPendingMessages($hasPendingChanges, $pendingUrl);
        return new ViewModel($variables);
    }

    public function deleteAction()
    {

        $id = $this->params('id');
        $query = $this->getServiceLocator()->get('wrapperQuery');
        $orgObj = $query->find('Organizations\Entity\Organization', $id);

        $orgModel = $this->getServiceLocator()->get('Organizations\Model\Organization');
        $orgModel->deleteOrganization($id);



        if ($orgObj->type == 1) {
            $url = $this->getEvent()->getRouter()->assemble(array('action' => 'atps'), array('name' => 'list_atc_orgs'));
        }
        else if ($orgObj->type == 2 || $data['type'] == 3) {
            $url = $this->getEvent()->getRouter()->assemble(array('action' => 'atcs'), array('name' => 'list_atp_orgs'));
        }
        $this->redirect()->toUrl($url);
    }

    public function saveStateAction()
    {
        $auth = new \Zend\Authentication\AuthenticationService();
        $creatorId = $auth->getIdentity()['id'];
        $query = $this->getServiceLocator()->get('wrapperQuery');
        $orgObj = new OrgEntity();
        $orgModel = $this->getServiceLocator()->get('Organizations\Model\Organization');
        $request = $this->getRequest();
        if ($request->isXmlHttpRequest()) {
            parse_str($_POST['saveState'], $stateArray);

            $rolesArray = $orgModel->getRequiredRoles($stateArray["type"]);
            $validationResult = $this->getServiceLocator()->get('aclValidator')->validateOrganizationAccessControl(/* $response = */$this->getResponse(), $rolesArray);
            if ($validationResult["isValid"] === false && !empty($validationResult["redirectUrl"])) {
                $data = array(
                    'result' => $validationResult["redirectUrl"],
                    'redirect' => true,
                );
            }


            if (!isset($data)) {
                // prepare dates 
                $stateArray['CRExpiration'] = null;
                $stateArray['atpLicenseExpiration'] = null;
                $stateArray['atcLicenseExpiration'] = null;

                if (!isset($stateArray['focalContactPerson_id']) || $stateArray['focalContactPerson_id'] == "") {
                    $stateArray['focalContactPerson_id'] = null;
                }
                if (!isset($stateArray['testCenterAdmin_id'])) {
                    $stateArray['testCenterAdmin_id'] = null;
                }
                if (!isset($stateArray['trainingManager_id'])) {
                    $stateArray['trainingManager_id'] = null;
                }

                $isUniqe = $orgModel->checkSavedBefore($stateArray['commercialName']);
                // check commercial name existance in DB
                if (!$isUniqe) {
                    // saving organizations as state saved organization
                    $stateArray['status'] = Status::STATUS_STATE_SAVED;
                    $stateArray['creatorId'] = $creatorId;

                    /**
                     * no need to assign users now so we used 
                     * save state = true .. now we will skip calling
                     * assignUserToOrg() method 
                     */
                    $orgModel->saveOrganization($stateArray, /* $orgObj = */ null, /* $oldStatus = */ null, /*$oldLongitude =*/ null, /*$oldLatitude =*/ null, /* $creatorId = */ null, /* $userEmail = */ null, /* $isAdminUser = */ true, /* $saveState = */ true);

                    $data = array(
                        'result' => true,
                    );
                }
                //uniqness error does not completed yet
                else {
                    $data = array(
                        'result' => "Commercial Name already Exists",
                    );
                }
            }
        }
        return $this->getResponse()->setContent(Json::encode($data));
    }

    /**
     * View pending version organization
     * 
     * 
     * @access public
     * 
     * @return ViewModel
     */
    public function pendingAction()
    {
        $variables = array();
        $id = $this->params('id');
        $query = $this->getServiceLocator()->get('wrapperQuery');
        $versionModel = $this->getServiceLocator()->get('Versioning\Model\Version');
        $organizationModel = $this->getServiceLocator()->get('Organizations\Model\Organization');
        $organization = $query->find('Organizations\Entity\Organization', $id);
        $isAdminUser = $this->isAdminUser();

        $organizationArray = array($organization);
        $organizationLogs = $versionModel->getLogEntriesPerEntities(/* $entities = */ $organizationArray, /* $objectIds = */ array(), /* $objectClass = */ null, /* $status = */ Status::STATUS_NOT_APPROVED);
        $organizationComparisonData = $versionModel->prepareDiffs($organizationArray, $organizationLogs);
        $organizationComparisonPreparedData = $organizationModel->prepareOrganizationDiff($organizationComparisonData);


        $variables['organization'] = $organizationComparisonPreparedData;
        $variables['isAdminUser'] = $isAdminUser;
        $variables['id'] = $id;
        return new ViewModel($variables);
    }

    /**
     * Approve pending version organization
     * 
     * 
     * @access public
     */
    public function approveAction()
    {
        $id = $this->params('id');
        $query = $this->getServiceLocator()->get('wrapperQuery');
        $versionModel = $this->getServiceLocator()->get('Versioning\Model\Version');
        $organization = $query->find('Organizations\Entity\Organization', $id);

        $organizationArray = array($organization);
        $organizationLogs = $versionModel->getLogEntriesPerEntities(/* $entities = */ $organizationArray, /* $objectIds = */ array(), /* $objectClass = */ null, /* $status = */ Status::STATUS_NOT_APPROVED);
        $versionModel->approveChanges($organizationArray, $organizationLogs);

        $url = $this->getEvent()->getRouter()->assemble(array('action' => 'index'), array('name' => 'organizationsList'));
        $this->redirect()->toUrl($url);
    }

    /**
     * Disapprove pending version organization
     * 
     * 
     * @access public
     */
    public function disapproveAction()
    {
        $id = $this->params('id');
        $query = $this->getServiceLocator()->get('wrapperQuery');
        $versionModel = $this->getServiceLocator()->get('Versioning\Model\Version');
        $organization = $query->find('Organizations\Entity\Organization', $id);

        $organizationArray = array($organization);
        $organizationLogs = $versionModel->getLogEntriesPerEntities(/* $entities = */ $organizationArray, /* $objectIds = */ array(), /* $objectClass = */ null, /* $status = */ Status::STATUS_NOT_APPROVED);
        $versionModel->disapproveChanges($organizationArray, $organizationLogs);

        $url = $this->getEvent()->getRouter()->assemble(array('action' => 'index'), array('name' => 'organizationsList'));
        $this->redirect()->toUrl($url);
    }

    /**
     * Download organization attachment
     * 
     * @access public
     * @return Zend\Http\Response\Stream
     */
    public function downloadAction()
    {
        $id = $this->params('id');
        $type = $this->params('type');
        $notApproved = $this->params('notApproved', /* $default = */ false);

        $query = $this->getServiceLocator()->get('wrapperQuery');
        $fileUtilities = $this->getServiceLocator()->get('fileUtilities');
        $organizationModel = $this->getServiceLocator()->get('Organizations\Model\Organization');

        $organization = $query->find('Organizations\Entity\Organization', /* $criteria = */ $id);
        $file = $organizationModel->getFile($organization, $type, $notApproved);

        return $fileUtilities->getFileResponse($file);
    }

    /**
     * Console action to update ExpirationDate Flag
     */
    public function updateExpirationFlagAction()
    {
        $organizationMetaModel = $this->getServiceLocator()->get('Organizations\Model\OrganizationMeta');
        $organizationMetaModel->updateExpirationFlag();
    }

    /**
     * listing organization that user assigned for
     * @return ViewModel
     */
    public function myOrganizationsAction()
    {
        $variables = array();
        $organizationMetaModel = $this->getServiceLocator()->get('Organizations\Model\OrganizationMeta');
        $organizationMetaModel->filterOragnizations();
        $pageNumber = $this->getRequest()->getQuery('page');
        $organizationMetaModel->setPage($pageNumber);

        $pageNumbers = $organizationMetaModel->getPagesRange($pageNumber);
        $nextPageNumber = $organizationMetaModel->getNextPageNumber($pageNumber);
        $previousPageNumber = $organizationMetaModel->getPreviousPageNumber($pageNumber);
        $variables['myOrganizations'] = $organizationMetaModel->getCurrentItems();
        $variables['pageNumbers'] = $pageNumbers;
        $variables['hasPages'] = ( count($pageNumbers) > 0 ) ? true : false;
        $variables['nextPageNumber'] = $nextPageNumber;
        $variables['previousPageNumber'] = $previousPageNumber;
        return new ViewModel($variables);
    }

}
