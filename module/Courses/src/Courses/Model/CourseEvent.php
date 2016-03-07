<?php

namespace Courses\Model;

use Zend\Authentication\AuthenticationService;
use Users\Entity\Role;
use Zend\Form\FormInterface;
use Doctrine\Common\Collections\Criteria;

/**
 * CourseEvent Model
 * 
 * Handles CourseEvent Entity related business
 * 
 * 
 * @property Utilities\Service\Query\Query $query
 * @property Utilities\Service\Object $objectUtilities
 * 
 * @package courses
 * @subpackage model
 */
class CourseEvent
{

    /**
     *
     * @var Utilities\Service\Query\Query 
     */
    protected $query;

    /**
     *
     * @var Utilities\Service\Object 
     */
    protected $objectUtilities;


    /**
     * Set needed properties
     * 
     * @access public
     * @param Utilities\Service\Query\Query $query
     * @param Utilities\Service\Object $objectUtilities
     */
    public function __construct($query, $objectUtilities)
    {
        $this->query = $query;
        $this->objectUtilities = $objectUtilities;
    }

    /**
     * Set can enroll property
     * 
     * @access public
     * @param array $courseEvents
     * @return array courseEvents with canRoll property added
     */
    public function setCanEnroll($courseEvents)
    {
        $auth = new AuthenticationService();
        $storage = $auth->getIdentity();
        $currentUser = NULL;
        if ($auth->hasIdentity()) {
            $currentUser = $this->query->find('Users\Entity\User', $storage['id']);
        }
        foreach ($courseEvents as $courseEvent) {
            $nonAuthorizedEnroll = false;
            $canEnroll = true;
            $users = $courseEvent->getUsers();
            $canLeave = false;
            if ($auth->hasIdentity()) {
                $courseEventAiId = $this->objectUtilities->getId($courseEvent->getAi());
                if (in_array(Role::INSTRUCTOR_ROLE, $storage['roles']) && $storage['id'] == $courseEventAiId) {
                    $nonAuthorizedEnroll = true;
                }
            }
            if (!is_null($currentUser)) {
                $canLeave = $users->contains($currentUser);
            }
            if ($canLeave === true || $nonAuthorizedEnroll === true || $courseEvent->getStudentsNo() >= $courseEvent->getCapacity()) {
                $canEnroll = false;
            }
            $courseEvent->canEnroll = $canEnroll;
            $courseEvent->canLeave = $canLeave;
        }
        return $courseEvents;
    }

   

    /**
     * Leave course event
     * 
     * @access public
     * @param Courses\Entity\CourseEvent $courseEvent
     * @param Users\Entity\User $user
     */
    public function leaveCourse($courseEvent, $user)
    {
        $users = $courseEvent->getUsers();
        $users->removeElement($user);
        $courseEvent->setUsers($users);

        $studentsNo = $courseEvent->getStudentsNo();
        $studentsNo--;
        $courseEvent->setStudentsNo($studentsNo);
        $this->query->setEntity('Courses\Entity\CourseEvent')->save($courseEvent);
    }

    /**
     * Enroll course
     * 
     * @access public
     * @param Courses\Entity\CourseEvent $courseEvent
     * @param Users\Entity\User $user
     * @throws \Exception Capacity exceeded
     */
    public function enrollCourse($courseEvent, $user)
    {
        $studentsNo = $courseEvent->getStudentsNo();
        $studentsNo++;

        $capacity = $courseEvent->getCapacity();
        if ($capacity < $studentsNo) {
            throw new \Exception("Capacity exceeded");
        }

        $courseEvent->setStudentsNo($studentsNo);
        $courseEvent->addUser($user);
        $this->query->setEntity('Courses\Entity\CourseEvent')->save($courseEvent);
    }

    /**
     * Validate courseEvent form
     * 
     * @access public
     * @param Courses\Form\CourseEventForm $form
     * @param array $data
     * @param Courses\Entity\CourseEvent $courseEvent ,default is null
     * @param bool $isEditForm ,default is true
     * @return bool custom validation result
     */
    public function validateForm($form, $data, $courseEvent = null, $isEditForm = true)
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
        if ($isCustomValidationValid === false && !is_null($courseEvent)) {
            $courseEvent->exchangeArray($data);
            $form->bind($courseEvent, /* $flags = */ FormInterface::VALUES_NORMALIZED, $isEditForm);
        }
        return $isCustomValidationValid;
    }
}
