<?php

namespace Courses\Controller;

use Utilities\Controller\ActionController;
use Zend\View\Model\ViewModel;
use Courses\Form\ResourceForm;
use Courses\Entity\Resource;
use Zend\Authentication\AuthenticationService;
use Users\Entity\Role;
use Zend\Form\FormInterface;

/**
 * Resource Controller
 * 
 * resources entries listing
 * 
 * 
 * 
 * @package courses
 * @subpackage controller
 */
class ResourceController extends ActionController
{

    /**
     * List resources
     * 
     * 
     * @access public
     * 
     * @return ViewModel
     */
    public function indexAction()
    {
        $variables = array();

        $courseId = $this->params('courseId', /* $default = */ null);
        $processResult = $this->params('processResult', /* $default = */ "true");
        if ($processResult === "true") {
            $processResult = true;
        }
        else {
            $processResult = false;
        }

        $query = $this->getServiceLocator()->get('wrapperQuery');
        if (!is_null($courseId)) {
            $validationResult = $this->getServiceLocator()->get('aclValidator')->validateOrganizationAccessControl(/* $response = */$this->getResponse(), /* $role = */ Role::TRAINING_MANAGER_ROLE);
            if ($validationResult["isValid"] === false && !empty($validationResult["redirectUrl"])) {
                return $this->redirect()->toUrl($validationResult["redirectUrl"]);
            }
        }
        $objectUtilities = $this->getServiceLocator()->get('objectUtilities');
        $auth = new AuthenticationService();
        $storage = $auth->getIdentity();
        $isAdminUser = false;
        if ($auth->hasIdentity()) {
            if (in_array(Role::ADMIN_ROLE, $storage['roles'])) {
                $isAdminUser = true;
            }
        }
        $isInstructor = true;
        if (in_array(Role::INSTRUCTOR_ROLE, $storage['roles'])) {
            $isInstructor = false;
        }


        $criteria = array();
        if (!empty($courseId)) {
            $criteria['course'] = $variables['courseId'] = $courseId;
        }
        $data = $query->findBy(/* $entityName = */'Courses\Entity\Resource', $criteria);
        $variables['resources'] = $objectUtilities->prepareForDisplay($data);
        $variables['isAdminUser'] = $isAdminUser;
        $variables['isInstructor'] = $isInstructor;
        $variables['processResult'] = $processResult;
        return new ViewModel($variables);
    }

    /**
     * Create new resource
     * 
     * 
     * @access public
     * @uses Resource
     * @uses ResourceForm
     * 
     * @return ViewModel
     */
    public function newAction()
    {
        $variables = array();
        $courseId = $this->params('courseId', /* $default = */ null);

        $query = $this->getServiceLocator()->get('wrapperQuery');
        if (!is_null($courseId)) {
            $validationResult = $this->getServiceLocator()->get('aclValidator')->validateOrganizationAccessControl(/* $response = */$this->getResponse(), /* $role = */ Role::TRAINING_MANAGER_ROLE);
            if ($validationResult["isValid"] === false && !empty($validationResult["redirectUrl"])) {
                return $this->redirect()->toUrl($validationResult["redirectUrl"]);
            }
        }
        $resourceModel = $this->getServiceLocator()->get('Courses\Model\Resource');
        $resource = new Resource();
        $auth = new AuthenticationService();
        $storage = $auth->getIdentity();
        $isAdminUser = false;
        if ($auth->hasIdentity()) {
            if (in_array(Role::ADMIN_ROLE, $storage['roles'])) {
                $isAdminUser = true;
            }
        }

        $options = array();
        $options['query'] = $query->setEntity('Courses\Entity\Resource');
        $options['courseId'] = $courseId;
        $form = new ResourceForm(/* $name = */ null, $options);

        $request = $this->getRequest();
        if ($request->isPost()) {
            // Make certain to merge the files info!
            $fileData = $request->getFiles()->toArray();

            $data = array_merge_recursive(
                    $request->getPost()->toArray(), $fileData
            );
            if (empty($courseId)) {
                $courseId = $data["course"];
            }
            else {
                $data["course"] = $courseId;
            }
            $form->setInputFilter($resource->getInputFilter($courseId, /* $name = */ $data["name"]));
            $form->setData($data);
            $validationOutput = $resourceModel->validateResources($form, $resource, $data);
            if ($validationOutput["isValid"]) {
                $formData = $form->getData(FormInterface::VALUES_AS_ARRAY);
                $formData["nameAdded"] = isset($data["nameAdded"]) ? $data["nameAdded"] : array();
                $formData["fileAdded"] = isset($data["fileAdded"]) ? $data["fileAdded"] : array();
                $resourceModel->save($resource, $formData, $isAdminUser);

                $url = $this->getEvent()->getRouter()->assemble(array('action' => 'edit', 'courseId' => $courseId), array('name' => 'resourcesEdit'));
                $this->redirect()->toUrl($url);
            }
            elseif (array_key_exists("addedResources", $validationOutput)) {
                $variables['addResourcesValidation'] = $validationOutput["addedResources"];
            }
        }
        $variables['courseId'] = $courseId;
        $variables['resourceForm'] = $this->getFormView($form);
        return new ViewModel($variables);
    }

    /**
     * Edit resource
     * 
     * 
     * @access public
     * @uses ResourceForm
     * 
     * @return ViewModel
     */
    public function editAction()
    {
        $variables = array();
        $courseId = $this->params('courseId', /* $default = */ null);

        $query = $this->getServiceLocator()->get('wrapperQuery');
        $resourceModel = $this->getServiceLocator()->get('Courses\Model\Resource');
        $course = $query->find('Courses\Entity\Course', $courseId);
        $resources = $course->getResources();
        $auth = new AuthenticationService();
        $storage = $auth->getIdentity();

        $isAdminUser = false;

        if ($auth->hasIdentity()) {
            $userEmail = $storage["email"];
            if (in_array(Role::ADMIN_ROLE, $storage['roles'])) {
                $isAdminUser = true;
            }
            if (in_array(Role::TRAINING_MANAGER_ROLE, $storage['roles'])) {
                $validationResult = $this->getServiceLocator()->get('aclValidator')->validateOrganizationAccessControl(/* $response = */$this->getResponse(), /* $role = */ Role::TRAINING_MANAGER_ROLE);
                if ($validationResult["isValid"] === false && !empty($validationResult["redirectUrl"])) {
                    return $this->redirect()->toUrl($validationResult["redirectUrl"]);
                }
            }
        }

        $variables['isAdminUser'] = $isAdminUser;

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost()->toArray();
            $resourceModel->updateListedResources($data, $isAdminUser, $userEmail);
        }
        
        $courseModel = $this->getServiceLocator()->get('Courses\Model\Course');
        $entitiesAndLogEntriesArray = $courseModel->getLogEntries($course);
        
        $variables['courseId'] = $courseId;
        $hasPendingChanges = $entitiesAndLogEntriesArray['hasPendingChanges'];
        $variables['resources'] = $resourceModel->listResourcesForEdit($resources);
        $pendingUrl = $this->getEvent()->getRouter()->assemble(array('id' => $courseId), array('name' => 'coursesPending'));
        $versionModel = $this->getServiceLocator()->get('Versioning\Model\Version');
        $variables['messages'] = $versionModel->getPendingMessages($hasPendingChanges, $pendingUrl);
        return new ViewModel($variables);
    }

    /**
     * Delete resource
     *
     * 
     * @access public
     */
    public function deleteAction()
    {
        $id = $this->params('id');
        $courseId = $this->params('courseId', /* $default = */ null);
        $query = $this->getServiceLocator()->get('wrapperQuery');
        $resourceModel = $this->getServiceLocator()->get('Courses\Model\Resource');
        $resource = $query->find('Courses\Entity\Resource', $id);

        $processResult = $resourceModel->remove($resource);


        $url = $this->getResourcesUrl($courseId);
        $url .= "/" . $processResult;
        $this->redirect()->toUrl($url);
    }

    /**
     * Download resource
     *
     * 
     * @access public
     */
    public function downloadAction()
    {
        $id = $this->params('id');
        $query = $this->getServiceLocator()->get('wrapperQuery');

        $resource = $query->find('Courses\Entity\Resource', /* $criteria = */ $id);
        $courseEventModel = $this->getServiceLocator()->get('Courses\Model\CourseEvent');
        $fileUtilities = $this->getServiceLocator()->get('fileUtilities');

        $courseArray = array($resource->getCourse());
        $preparedCourseArray = $courseEventModel->setCourseEventsPrivileges($courseArray);
        $preparedCourse = reset($preparedCourseArray);
        $auth = new AuthenticationService();
        $storage = $auth->getIdentity();
        $canDownload = true;
        if ($auth->hasIdentity()) {
            if (in_array(Role::STUDENT_ROLE, $storage['roles']) && is_object($preparedCourse) && property_exists($preparedCourse, 'canDownload') && $preparedCourse->canDownload === false) {
                $canDownload = false;
            }
        }

        if ($canDownload === false) {
            $this->getResponse()->setStatusCode(302);
            $url = $this->getEvent()->getRouter()->assemble(array(), array('name' => 'noaccess'));
            $this->redirect()->toUrl($url);
        }
        else {
            $file = $resource->getFile()["tmp_name"];
            return $fileUtilities->getFileResponse($file);
        }
    }

    /**
     * Get resources index url
     * 
     * @access private
     * @param int $courseId ,default is null
     * 
     * @return string url
     */
    private function getResourcesUrl($courseId = null)
    {
        $routeName = "resources";
        $params = array('action' => 'index');
        if (!empty($courseId)) {
            $params['courseId'] = $courseId;
            $routeName = "resourcesListPerCourse";
        }
        return $this->getEvent()->getRouter()->assemble($params, array('name' => $routeName));
    }

    public function editRecourceAction()
    {
        $variables = array();
        $id = $this->params('id');
        $courseId = $this->params('courseId', /* $default = */ null);

        $query = $this->getServiceLocator()->get('wrapperQuery');
        $resourceModel = $this->getServiceLocator()->get('Courses\Model\Resource');
        $resource = $query->find('Courses\Entity\Resource', $id);
        $auth = new AuthenticationService();
        $storage = $auth->getIdentity();
        $isAdminUser = false;
        if ($auth->hasIdentity()) {
            if (in_array(Role::ADMIN_ROLE, $storage['roles'])) {
                $isAdminUser = true;
            }
            elseif (in_array(Role::TRAINING_MANAGER_ROLE, $storage['roles'])) {
                if ($resource->getCourse()->getId() != $courseId) {
                    $url = $this->getEvent()->getRouter()->assemble(array("id" => $resource->getId(), "courseId" => $resource->getCourse()->getId()), array('name' => 'resourcesEditPerCourse'));
                    $this->redirect()->toUrl($url);
                }
                $validationResult = $this->getServiceLocator()->get('aclValidator')->validateOrganizationAccessControl(/* $response = */$this->getResponse(), /* $role = */ Role::TRAINING_MANAGER_ROLE);
                if ($validationResult["isValid"] === false && !empty($validationResult["redirectUrl"])) {
                    return $this->redirect()->toUrl($validationResult["redirectUrl"]);
                }
            }
        }

        $options = array();
        $options['query'] = $query->setEntity('Courses\Entity\Resource');
        $options['courseId'] = $courseId;
        $form = new ResourceForm(/* $name = */ null, $options);
        $form->bind($resource);

        $request = $this->getRequest();
        if ($request->isPost()) {
            // Make certain to merge the files info!
            $fileData = $request->getFiles()->toArray();

            $data = array_merge_recursive(
                    $request->getPost()->toArray(), $fileData
            );
            if (empty($courseId)) {
                $courseId = $data["course"];
            }
            else {
                $data["course"] = $courseId;
            }
            $form->setInputFilter($resource->getInputFilter($courseId, /* $name = */ $data["name"]));

            $inputFilter = $form->getInputFilter();
            $form->setData($data);
            // file not updated
            if (isset($fileData['file']['name']) && empty($fileData['file']['name'])) {
                // Change required flag to false for any previously uploaded files
                $input = $inputFilter->get('file');
                $input->setRequired(false);
            }
            if ($form->isValid()) {
                $resourceModel->save($resource, /* $data = */ array(), $isAdminUser);

                $url = $this->getResourcesUrl($courseId);
                $this->redirect()->toUrl($url);
            }
        }

        $variables['resourceForm'] = $this->getFormView($form);
        return new ViewModel($variables);
    }

}
