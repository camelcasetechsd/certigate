<?php

namespace Courses\Form;

use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Courses\Entity\CourseEvent;
use CustomDoctrine\Service\DoctrineObject as DoctrineHydrator;
use Utilities\Service\Time;
use Users\Entity\Role;
use Utilities\Form\Form;
use Organizations\Entity\OrganizationType;
use Utilities\Service\Status;

/**
 * CourseEvent Fieldset
 * 
 * Handles CourseEvent form setup
 * 
 * @property Utilities\Service\Query\Query $query
 * @property bool $isAdminUser
 * @property int $userId
 * 
 * @package courses
 * @subpackage form
 */
class CourseEventFieldset extends Fieldset implements InputFilterProviderInterface
{

    /**
     *
     * @var Utilities\Service\Query\Query 
     */
    protected $query;

    /**
     *
     * @var bool
     */
    protected $isAdminUser;

    /**
     *
     * @var int
     */
    protected $userId;
    
    /**
     * setup form
     * 
     * 
     * @access public
     * @param Utilities\Service\Query\Query  $query
     * @param bool $isAdminUser
     * @param int $userId
     */
    public function __construct($query, $isAdminUser, $userId)
    {
        $this->query = $query;
        $this->isAdminUser = $isAdminUser;
        $this->userId = $userId;
        parent::__construct(/*$name =*/ "courseEvent");

        $this->setHydrator(new DoctrineHydrator($this->query->entityManager))
             ->setObject(new CourseEvent())
         ;
        
        $types = array(OrganizationType::TYPE_ATP_TITLE);
        $userIds = array();
        if ($this->isAdminUser === false) {
            $userIds[] = $this->userId;
        }
        $this->add(array(
            'name' => 'atp',
            'type' => 'DoctrineModule\Form\Element\ObjectSelect',
            'attributes' => array(
                'required' => 'required',
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => 'Training Partner',
                'object_manager' => $this->query->entityManager,
                'target_class' => 'Organizations\Entity\Organization',
                'property' => 'commercialName',
                'is_method' => false,
                'find_method' => array(
                    'name' => 'getOrganizationsBy',
                    'params' => array(
                        'userIds' => $userIds,
                        'types' => $types,
                        'status' => Status::STATUS_ACTIVE,
                    )
                ),
                'empty_item_label' => Form::EMPTY_SELECT_VALUE,
                'display_empty_item' => true,
            ),
        ));
        $this->add(array(
            'name' => 'ai',
            'type' => 'DoctrineModule\Form\Element\ObjectSelect',
            'attributes' => array(
                'required' => 'required',
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => 'Instructor',
                'object_manager' => $this->query->entityManager,
                'target_class' => 'Users\Entity\User',
                'property' => 'fullName',
                'is_method' => true,
                'find_method' => array(
                    'name' => 'getUsers',
                    'params' => array(
                        "roles" => array(
                            Role::INSTRUCTOR_ROLE
                        )
                    )
                ),
                'empty_item_label' => Form::EMPTY_SELECT_VALUE,
                'display_empty_item' => true,
            ),
        ));

        $this->add(array(
            'name' => 'startDate',
            'type' => 'Zend\Form\Element\Date',
            'attributes' => array(
                'required' => 'required',
                'class' => 'form-control date',
                'type' => 'text',
            ),
            'options' => array(
                'label' => 'Start Date',
                'format' => Time::DATE_FORMAT,
            ),
        ));
        $this->add(array(
            'name' => 'endDate',
            'type' => 'Zend\Form\Element\Date',
            'attributes' => array(
                'required' => 'required',
                'class' => 'form-control date',
                'type' => 'text',
            ),
            'options' => array(
                'label' => 'End Date',
                'format' => Time::DATE_FORMAT,
            ),
        ));

    }

    /**
     * Get inputfilter specification
     * 
     * @access public
     * @return array
     */
    public function getInputFilterSpecification()
    {
        return array(
            'startDate' => array(
                'required' => true,
            ),
            'endDate' => array(
                'required' => true,
            ),
            'atp' => array(
                'required' => true,
            ),
            'ai' => array(
                'required' => true,
            ),
        );
    }

}
