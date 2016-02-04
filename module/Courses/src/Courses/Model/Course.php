<?php

namespace Courses\Model;

use Zend\Authentication\AuthenticationService;
use Users\Entity\Role;
use Utilities\Service\Status;
use Zend\Form\FormInterface;
use System\Service\Settings;
use Notifications\Service\MailTempates;
use Notifications\Service\MailSubjects;
use System\Service\Cache\CacheHandler;

/**
 * Course Model
 * 
 * Handles Course Entity related business
 * 
 * 
 * @property Utilities\Service\Query\Query $query
 * @property Courses\Model\Outline $outlineModel
 * @property System\Service\Cache\CacheHandler $systemCacheHandler
 * @property Notifications\Service\Notification $notification
 * 
 * @package courses
 * @subpackage model
 */
class Course
{

    /**
     *
     * @var Utilities\Service\Query\Query 
     */
    protected $query;

    /**
     *
     * @var Courses\Model\Outline
     */
    protected $outlineModel;

    /**
     *
     * @var System\Service\Cache\CacheHandler
     */
    protected $systemCacheHandler;
    
    /**
     *
     * @var Notifications\Service\Notification
     */
    protected $notification;
    
    /**
     * Set needed properties
     * 
     * @access public
     * @param Utilities\Service\Query\Query $query
     * @param Courses\Model\Outline $outlineModel
     * @param System\Service\Cache\CacheHandler $systemCacheHandler
     * @param Notifications\Service\Notification $notification
     */
    public function __construct($query, $outlineModel, $systemCacheHandler, $notification)
    {
        $this->query = $query;
        $this->outlineModel = $outlineModel;
        $this->systemCacheHandler = $systemCacheHandler;
        $this->notification = $notification;
    }

    /**
     * Set can enroll property
     * 
     * @access public
     * @param array $courses
     * @return array courses with canRoll property added
     */
    public function setCanEnroll($courses)
    {
        $auth = new AuthenticationService();
        $storage = $auth->getIdentity();
        $currentUser = NULL;
        if ($auth->hasIdentity()) {
            $currentUser = $this->query->find('Users\Entity\User', $storage['id']);
        }
        foreach ($courses as $course) {
            $nonAuthorizedEnroll = false;
            $canEnroll = true;
            $users = $course->getUsers();
            $canLeave = false;
            if ($auth->hasIdentity()) {
                if (in_array(Role::INSTRUCTOR_ROLE, $storage['roles']) && $storage['id'] == $course->getAi()->getId()) {
                    $nonAuthorizedEnroll = true;
                }
            }
            if (!is_null($currentUser)) {
                $canLeave = $users->contains($currentUser);
            }
            if ($canLeave === true || $nonAuthorizedEnroll === true || $course->getStudentsNo() >= $course->getCapacity()) {
                $canEnroll = false;
            }
            $course->canEnroll = $canEnroll;
            $course->canLeave = $canLeave;
        }
        return $courses;
    }

    /**
     * Save course
     * 
     * @access public
     * @param Courses\Entity\Course $course
     * @param array $data ,default is empty array
     * @param bool $isAdminUser ,default is bool false
     * @param bool $oldStatus ,default is null
     * @param string $userEmail ,default is null
     */
    public function save($course, $data = array(), $isAdminUser = false, $oldStatus = null, $userEmail = null)
    {
        $notifyAdminFlag = false;
        if ($isAdminUser === false) {
            // edit case where data is empty array
            if (count($data) == 0) {
                $course->setStatus($oldStatus);
            }
            else {
                $course->setStatus(Status::STATUS_NOT_APPROVED);
                $notifyAdminFlag = true;
            }
        }
        unset($data["outlines"]);
        $this->query->setEntity("Courses\Entity\Course")->save($course, $data, /* $flushAll = */ true);

        // remove not needed outlines        
        $this->outlineModel->cleanUpOutlines();
        
        if($notifyAdminFlag === true){
            $this->sendMail($userEmail);
        }
    }

    /**
     * Send mail
     * 
     * @param string $userEmail
     * @throws \Exception From email is not set
     * @throws \Exception To email is not set
     */
    private function sendMail($userEmail)
    {
        $forceFlush = (APPLICATION_ENV == "production" ) ? false : true;
        $cachedSystemData = $this->systemCacheHandler->getCachedSystemData($forceFlush);
        $settings = $cachedSystemData[CacheHandler::SETTINGS_KEY];

        if (array_key_exists(Settings::SYSTEM_EMAIL, $settings)) {
            $from = $settings[Settings::SYSTEM_EMAIL];
        }
        if (array_key_exists(Settings::ADMIN_EMAIL, $settings)) {
            $to = $settings[Settings::ADMIN_EMAIL];
        }

        if (!isset($from)) {
            throw new \Exception("From email is not set");
        }
        if (!isset($to)) {
            throw new \Exception("To email is not set");
        }
        $templateParameters = array(
            "email" => $userEmail,
        );

        $mailArray = array(
            'to' => $to,
            'from' => $from,
            'templateName' => MailTempates::NEW_COURSE_NOTIFICATION_TEMPLATE,
            'templateParameters' => $templateParameters,
            'subject' => MailSubjects::NEW_COURSE_NOTIFICATION_SUBJECT,
        );
        $this->notification->notify($mailArray);
    }
    
    /**
     * Leave course
     * 
     * @access public
     * @param Courses\Entity\Course $course
     * @param Users\Entity\User $user
     */
    public function leaveCourse($course, $user)
    {
        $users = $course->getUsers();
        $users->removeElement($user);
        $course->setUsers($users);

        $studentsNo = $course->getStudentsNo();
        $studentsNo--;
        $course->setStudentsNo($studentsNo);
        $this->query->setEntity('Courses\Entity\Course')->save($course);
    }

    /**
     * Enroll course
     * 
     * @access public
     * @param Courses\Entity\Course $course
     * @param Users\Entity\User $user
     * @throws \Exception Capacity exceeded
     */
    public function enrollCourse($course, $user)
    {
        $studentsNo = $course->getStudentsNo();
        $studentsNo++;

        $capacity = $course->getCapacity();
        if ($capacity < $studentsNo) {
            throw new \Exception("Capacity exceeded");
        }

        $course->setStudentsNo($studentsNo);
        $course->addUser($user);
        $this->query->setEntity('Courses\Entity\Course')->save($course);
    }

    /**
     * Validate course form
     * 
     * @access public
     * @param Courses\Form\CourseForm $form
     * @param array $data
     * @param Courses\Entity\Course $course ,default is null
     * @param bool $isEditForm ,default is true
     * @return bool custom validation result
     */
    public function validateForm($form, $data, $course = null, $isEditForm = true)
    {
        $isCustomValidationValid = true;
        if ((int) $data['capacity'] < (int) $data['studentsNo']) {
            $form->get('capacity')->setMessages(array("Capacity should be higher than enrolled students number"));
            $isCustomValidationValid = false;
        }
        $endDate = strtotime(str_replace('/', '-', $data['endDate']));
        $startDate = strtotime(str_replace('/', '-', $data['startDate']));
        if ($endDate < $startDate) {
            $form->get('endDate')->setMessages(array("End date should be after Start date"));
            $isCustomValidationValid = false;
        }
        // retrieve old data if custom validation failed to pass
        if ($isCustomValidationValid === false && !is_null($course)) {
            $courseOutlines = $form->getObject()->getOutlines();
            $course->exchangeArray($data);
            $course->setOutlines($courseOutlines);
            $form->bind($course,/* $flags = */ FormInterface::VALUES_NORMALIZED, $isEditForm);
        }
        return $isCustomValidationValid;
    }

    public function saveEvaluation($evalObj, $data, $isAdmin)
    {
        if ($isAdmin) {
            $evalObj->setIsAdmin(\Courses\Entity\Evaluation::ADMIN_CREATED);
            $this->query->setEntity("Courses\Entity\Evaluation")->save($evalObj, $data);
            $courses = $this->query->findAll("Courses\Entity\Course");
            $eval = $this->query->findBy("Courses\Entity\Evaluation", array('questionTitle' => $evalObj->getQuestionTitle()));
            foreach ($courses as $course) {
                $course->setEvaluation($eval[0]);
                $this->query->setEntity("Courses\Entity\Course")->save($course);
            }
        }
        else {
            $evalObj->setIsAdmin(\Courses\Entity\Evaluation::USER_CREATED);
            $this->query->setEntity("Courses\Entity\Course")->save($evalObj, $data);
        }
    }

    /**
     * this function meant to list all courses assigned to user if instructor
     */
    public function prepareInstructorCourses($userId)
    {
        //desired courses which user is assigned to
        $courses = array();
        $allCourses = $this->query->findAll('Courses\Entity\Course');
        foreach ($allCourses as $course) {
            if ($course->getAi()->id == $userId) {
                var_dump('here');
                array_push($courses, $course);
            }
        }
        return $courses;
    }

}
