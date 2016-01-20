<?php

namespace Courses\Controller;

use Utilities\Controller\ActionController;
use Zend\View\Model\ViewModel;
use Courses\Form\ResourceForm;
use Courses\Entity\Resource;
use Zend\Authentication\AuthenticationService;
use Users\Entity\Role;
use Utilities\Service\Status;
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

        $query = $this->getServiceLocator()->get('wrapperQuery')->setEntity('Courses\Entity\Course');
        $objectUtilities = $this->getServiceLocator()->get('objectUtilities');
        $auth = new AuthenticationService();
        $storage = $auth->getIdentity();
        $isAdminUser = false;
        if ($auth->hasIdentity() && in_array(Role::ADMIN_ROLE, $storage['roles'])) {
            $isAdminUser = true;
        }

        $criteria = array();
        if (!empty($courseId)) {
            $criteria['course'] = $variables['courseId'] = $courseId;
        }
        $data = $query->findBy(/* $entityName = */null, $criteria);
        $variables['resources'] = $objectUtilities->prepareForDisplay($data);
        $variables['isAdminUser'] = $isAdminUser;
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

        $query = $this->getServiceLocator()->get('wrapperQuery')->setEntity('Courses\Entity\Resource');
        $resourceModel = $this->getServiceLocator()->get('Courses\Model\Resource');
        $resource = new Resource();
        $auth = new AuthenticationService();
        $storage = $auth->getIdentity();
        $isAdminUser = false;
        if ($auth->hasIdentity() && in_array(Role::ADMIN_ROLE, $storage['roles'])) {
            $isAdminUser = true;
        }

        $options = array();
        $options['query'] = $query;
        $options['isAdminUser'] = $isAdminUser;
        $form = new ResourceForm(/* $name = */ null, $options);

        $request = $this->getRequest();
        if ($request->isPost()) {
            // Make certain to merge the files info!
            $fileData = $request->getFiles()->toArray();

            $data = array_merge_recursive(
                    $request->getPost()->toArray(), $fileData
            );
            $form->setInputFilter($resource->getInputFilter());
            $form->setData($data);
            if ($form->isValid()) {
                $data = $form->getData(FormInterface::VALUES_AS_ARRAY);
                $resourceModel->save($resource, $data, $isAdminUser);

                $url = $this->getResourcesUrl($courseId);
                $this->redirect()->toUrl($url);
            }
        }

        $variables['resourceForm'] = $this->getFormView($form);
        return new ViewModel($variables);
    }

    /**
     * Edit course
     * 
     * 
     * @access public
     * @uses CourseForm
     * 
     * @return ViewModel
     */
    public function editAction()
    {
        $variables = array();
        $id = $this->params('id');
        $query = $this->getServiceLocator()->get('wrapperQuery');
        $courseModel = $this->getServiceLocator()->get('Courses\Model\Course');
        $course = $query->find('Courses\Entity\Course', $id);
        $oldStatus = $course->getStatus();
        $auth = new AuthenticationService();
        $storage = $auth->getIdentity();
        $isAdminUser = false;
        if ($auth->hasIdentity() && in_array(Role::ADMIN_ROLE, $storage['roles'])) {
            $isAdminUser = true;
        }

        $options = array();
        $options['query'] = $query;
        $options['isAdminUser'] = $isAdminUser;
        $form = new CourseForm(/* $name = */ null, $options);
        $form->bind($course);

        $request = $this->getRequest();
        if ($request->isPost()) {
            // Make certain to merge the files info!
            $fileData = $request->getFiles()->toArray();

            $data = array_merge_recursive(
                    $request->getPost()->toArray(), $fileData
            );
            $form->setInputFilter($course->getInputFilter());

            $inputFilter = $form->getInputFilter();
            $form->setData($data);
            // file not updated
            if (isset(reset($fileData['presentations'])['name']) && empty(reset($fileData['presentations'])['name'])) {
                // Change required flag to false for any previously uploaded files
                $input = $inputFilter->get('presentations');
                $input->setRequired(false);
            }
            if (isset($fileData['activities']['name']) && empty($fileData['activities']['name'])) {
                // Change required flag to false for any previously uploaded files
                $input = $inputFilter->get('activities');
                $input->setRequired(false);
            }
            if (isset($fileData['exams']['name']) && empty($fileData['exams']['name'])) {
                // Change required flag to false for any previously uploaded files
                $input = $inputFilter->get('exams');
                $input->setRequired(false);
            }
            if ($form->isValid()) {
                $courseModel->save($course, /* $data = */ array(), $isAdminUser, $oldStatus);

                $url = $this->getEvent()->getRouter()->assemble(array('action' => 'index'), array('name' => 'courses'));
                $this->redirect()->toUrl($url);
            }
        }

        $variables['courseForm'] = $this->getFormView($form);
        return new ViewModel($variables);
    }

    /**
     * Delete course
     *
     * 
     * @access public
     */
    public function deleteAction()
    {
        $id = $this->params('id');
        $query = $this->getServiceLocator()->get('wrapperQuery');
        $course = $query->find('Courses\Entity\Course', $id);

        $course->setStatus(Status::STATUS_INACTIVE);

        $query->save($course);

        $url = $this->getEvent()->getRouter()->assemble(array('action' => 'index'), array('name' => 'courses'));
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
        $resource = $this->params('resource');
        $name = $this->params('name');
        $query = $this->getServiceLocator()->get('wrapperQuery');
        $course = $query->find('Courses\Entity\Course', $id);
        $courseModel = $this->getServiceLocator()->get('Courses\Model\Course');


        $courseArray = array($course);
        $preparedCourseArray = $courseModel->setCanEnroll($courseArray);
        $preparedCourse = reset($preparedCourseArray);
        $auth = new AuthenticationService();
        $storage = $auth->getIdentity();
        if ($auth->hasIdentity() && in_array(Role::STUDENT_ROLE, $storage['roles']) && $preparedCourse->canLeave === false) {
            $this->getResponse()->setStatusCode(302);
            $url = $this->getEvent()->getRouter()->assemble(array(), array('name' => 'noaccess'));
            $this->redirect()->toUrl($url);
        }
        else {

            $file = $courseModel->getResource($course, $resource, $name);
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
     * @param int $id ,default is null
     * 
     * @return string url
     */
    private function getResourcesUrl($id = null)
    {
        $routeName = "resources";
        $params = array('action' => 'index');
        if (!empty($id)) {
            $params['id'] = $id;
            $routeName = "resourcesListPerCourse";
        }
        return $this->getEvent()->getRouter()->assemble($params, array('name' => $routeName));
    }

}
