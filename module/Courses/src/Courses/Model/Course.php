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
class Course {

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
    public function __construct($query) {
        $this->query = $query;
    }
    
    /**
     * Set can enroll property
     * 
     * @access public
     * @param array $courses
     * @return array courses with canRoll property added
     */
    public function setCanEnroll($courses) {
        $auth = new AuthenticationService();
        $storage = $auth->getIdentity();
        $nonAuthorizedEnroll = false;
        if ($auth->hasIdentity() && in_array( Role::INSTRUCTOR_ROLE, $storage['roles'] )) {
            $nonAuthorizedEnroll = true;
        }  
        foreach($courses as $course){
            $canEnroll = true;
            if($nonAuthorizedEnroll === true || $course->getStudentsNo() >= $course->getCapacity()){
                $canEnroll = false;
            }
            $course->canEnroll = $canEnroll;
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
     */
    public function save($course, $data = array(), $isAdminUser = false) {
        
        if($isAdminUser === false){
            $course->setStatus(Status::STATUS_NOT_APPROVED);
        }
        $this->query->setEntity("Courses\Entity\Course")->save($course, $data);
    }

}
