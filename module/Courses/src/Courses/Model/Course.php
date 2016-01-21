<?php

namespace Courses\Model;

use Zend\Authentication\AuthenticationService;
use Users\Entity\Role;
use Utilities\Service\Status;

/**
 * Course Model
 * 
 * Handles Course Entity related business
 * 
 * 
 * @property Utilities\Service\Query\Query $query
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
     * Set needed properties
     * 
     * @access public
     * @param Utilities\Service\Query\Query $query
     */
    public function __construct($query)
    {
        $this->query = $query;
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
        $nonAuthorizedEnroll = false;
        $currentUser = NULL;
        if ($auth->hasIdentity()) {
            $currentUser = $this->query->find('Users\Entity\User', $storage['id']);
            if (in_array(Role::INSTRUCTOR_ROLE, $storage['roles'])) {
                $nonAuthorizedEnroll = true;
            }
        }
        foreach ($courses as $course) {
            $canEnroll = true;
            $users = $course->getUsers();
            $canLeave = false;
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
     */
    public function save($course, $data = array(), $isAdminUser = false, $oldStatus = null)
    {

        if ($isAdminUser === false) {
            // edit case where data is empty array
            if (count($data) == 0) {
                $course->setStatus($oldStatus);
            }
            else {
                $course->setStatus(Status::STATUS_NOT_APPROVED);
            }
        }
        /////////////////
        $adminEvaluations = $this->query->findBy('Courses\Entity\Evaluation', array(
            'isAdmin' => 1
        ));
        foreach ($adminEvaluations as $eval) {
            $course->setEvaluation($eval);
        }
        ////////////////////////
        $this->query->setEntity("Courses\Entity\Course")->save($course, $data);
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

}
