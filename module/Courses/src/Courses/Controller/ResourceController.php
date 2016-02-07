<?php

namespace Courses\Controller;

use Utilities\Controller\ActionController;
use Zend\View\Model\ViewModel;
use Courses\Form\ResourceForm;
use Courses\Entity\Resource;
use Zend\Authentication\AuthenticationService;
use Users\Entity\Role;
use Zend\Form\FormInterface;
use Zend\Http\Response\Stream;
use Zend\Http\Headers;

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
            $course = $query->find(/* $entityName = */'Courses\Entity\Course', $courseId);
            $validationResult = $validationResult = $this->getServiceLocator()->get('aclValidator')->validateOrganizationAccessControl(/* $response = */$this->getResponse(), /* $role = */ Role::TRAINING_MANAGER_ROLE, /* $organization = */ $course->getAtp());
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
            $course = $query->find(/* $entityName = */'Courses\Entity\Course', $courseId);
            $validationResult = $validationResult = $this->getServiceLocator()->get('aclValidator')->validateOrganizationAccessControl(/* $response = */$this->getResponse(), /* $role = */ Role::TRAINING_MANAGER_ROLE, /* $organization = */ $course->getAtp());
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
        $options['isAdminUser'] = $isAdminUser;
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

                $url = $this->getResourcesUrl($courseId);
                $this->redirect()->toUrl($url);
            }
            else {
                $variables['addResourcesValidation'] = $validationOutput["addedResources"];
            }
        }

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
        $id = $this->params('id');
        $courseId = $this->params('courseId', /* $default = */ null);

        $query = $this->getServiceLocator()->get('wrapperQuery');
        $resourceModel = $this->getServiceLocator()->get('Courses\Model\Resource');
        $resource = $query->find('Courses\Entity\Resource', $id);
        $oldStatus = $resource->getStatus();
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
                $validationResult = $validationResult = $this->getServiceLocator()->get('aclValidator')->validateOrganizationAccessControl(/* $response = */$this->getResponse(), /* $role = */ Role::TRAINING_MANAGER_ROLE, /* $organization = */ $resource->getCourse()->getAtp());
                if ($validationResult["isValid"] === false && !empty($validationResult["redirectUrl"])) {
                    return $this->redirect()->toUrl($validationResult["redirectUrl"]);
                }
            }
        }

        $options = array();
        $options['query'] = $query->setEntity('Courses\Entity\Resource');
        $options['isAdminUser'] = $isAdminUser;
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
                $resourceModel->save($resource, /* $data = */ array(), $isAdminUser, $oldStatus);

                $url = $this->getResourcesUrl($courseId);
                $this->redirect()->toUrl($url);
            }
        }

        $variables['resourceForm'] = $this->getFormView($form);
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
        $course = $resource->getCourse();
        $courseModel = $this->getServiceLocator()->get('Courses\Model\Course');


        $courseArray = array($course);
        $preparedCourseArray = $courseModel->setCanEnroll($courseArray);
        $preparedCourse = reset($preparedCourseArray);
        $auth = new AuthenticationService();
        $storage = $auth->getIdentity();
        $canDownload = true;
        if ($auth->hasIdentity()) {
            if (in_array(Role::STUDENT_ROLE, $storage['roles']) && $preparedCourse->canLeave === false) {
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
            $response = new Stream();
            $response->setStream(fopen($file, 'r'));
            $response->setStatusCode(200);
            $response->setStreamName(basename($file));
            $headers = new Headers();
            $headers->addHeaders(array(
                'Content-Disposition' => 'attachment; filename="' . basename($file) . '"',
                'Content-Type' => 'application/octet-stream',
                'Content-Length' => filesize($file),
                'Expires' => '@0', // @0, because zf2 parses date as string to \DateTime() object
                'Cache-Control' => 'must-revalidate',
                'Pragma' => 'public'
            ));
            $response->setHeaders($headers);
            return $response;
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

}
