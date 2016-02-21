<?php

namespace Courses\Form;

use Utilities\Form\Form;
use Utilities\Service\Status;
use Users\Entity\Role;
use Utilities\Service\Time;
use Organizations\Entity\Organization;
use CustomDoctrine\Service\DoctrineObject as DoctrineHydrator;
use Courses\Form\OutlineFieldset;

/**
 * Course Form
 * 
 * Handles Course form setup
 * 
 * @property Utilities\Service\Query\Query $query
 * @property int $userId
 * 
 * @package courses
 * @subpackage form
 */
class CourseForm extends Form
{

    /**
     *
     * @var Utilities\Service\Query\Query 
     */
    protected $query;

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
     * @param string $name ,default is null
     * @param array $options ,default is null
     */
    public function __construct($name = null, $options = null)
    {
        $this->needAdminApproval = true;
        $this->query = $options['query'];
        unset($options['query']);
        $this->userId = $options['userId'];
        unset($options['userId']);
        parent::__construct($name, $options);

        // The form will hydrate an object of type "BlogPost"
        $this->setHydrator(new DoctrineHydrator($this->query->entityManager));

        $this->setAttribute('class', 'form form-horizontal');

        $this->add(array(
            'name' => 'name',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
                'required' => 'required',
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => 'Name',
            ),
        ));

        // Add the outline fieldset
        $outlineFieldset = new OutlineFieldset($this->query, $this->isAdminUser);
        $this->add(array(
            'type' => 'Zend\Form\Element\Collection',
            'name' => 'outlines',
            'options' => array(
                'count' => 1,
                'label' => "Outline",
                'should_create_template' => true,
                'allow_add' => true,
                'allow_remove' => true,
                'template_placeholder' => '__outlineNumber__',
                'target_element' => $outlineFieldset,
            ),
        ));
        $this->add(array(
            'name' => 'addMore',
            'type' => 'Zend\Form\Element',
            'attributes' => array(
                'class' => 'btn btn-primary addMoreButton',
                'value' => 'Add More',
                'type' => 'button',
                'onclick' => "addMoreOutline('#course_form_addMore')"
            )
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

        $this->add(array(
            'name' => 'capacity',
            'type' => 'Zend\Form\Element\Number',
            'attributes' => array(
                'required' => 'required',
                'class' => 'form-control',
                'min' => '1',
            ),
            'options' => array(
                'label' => 'Capacity',
            ),
        ));

        $this->add(array(
            'name' => 'studentsNo',
            'type' => 'Zend\Form\Element\Number',
            'attributes' => array(
                'required' => 'required',
                'class' => 'form-control',
                'min' => '0',
            ),
            'options' => array(
                'label' => 'Students Number',
            ),
        ));

        $types = array(Organization::TYPE_ATP, Organization::TYPE_BOTH);
        $status = Status::STATUS_ACTIVE;
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
                'label' => 'Authorized Training Partner',
                'object_manager' => $this->query->entityManager,
                'target_class' => 'Organizations\Entity\Organization',
                'property' => 'commercialName',
                'is_method' => false,
                'find_method' => array(
                    'name' => 'getOrganizationsBy',
                    'params' => array(
                        'userIds' => $userIds,
                        'types' => $types,
                        'status' => $status,
                    )
                ),
                'empty_item_label' => self::EMPTY_SELECT_VALUE,
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
                'label' => 'Authorized Instructor',
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
                'empty_item_label' => self::EMPTY_SELECT_VALUE,
                'display_empty_item' => true,
            ),
        ));

        $this->add(array(
            'name' => 'brief',
            'type' => 'Zend\Form\Element\Textarea',
            'attributes' => array(
                'required' => 'required',
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => 'Brief',
            ),
        ));

        $this->add(array(
            'name' => 'time',
            'type' => 'Zend\Form\Element\Time',
            'attributes' => array(
                'placeholder' => 'Example: 10:10',
                'required' => 'required',
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => 'Time',
                'format' => 'H:i'
            ),
        ));

        $this->add(array(
            'name' => 'duration',
            'type' => 'Zend\Form\Element\Number',
            'attributes' => array(
                'placeholder' => 'Duration is in days',
                'required' => 'required',
                'class' => 'form-control',
                'min' => '1',
            ),
            'options' => array(
                'label' => 'Duration',
            ),
        ));

        if ($this->isAdminUser === true) {
            $this->add(array(
                'name' => 'isForInstructor',
                'type' => 'Zend\Form\Element\Checkbox',
                'attributes' => array(
                    'class' => 'form-control',
                ),
                'options' => array(
                    'label' => 'Train the Trainer course',
                    'checked_value' => Status::STATUS_ACTIVE,
                    'unchecked_value' => Status::STATUS_INACTIVE
                ),
            ));
            $this->add(array(
                'name' => 'status',
                'type' => 'Zend\Form\Element\Checkbox',
                'attributes' => array(
                    'class' => 'form-control',
                ),
                'options' => array(
                    'label' => 'Status',
                    'checked_value' => Status::STATUS_ACTIVE,
                    'unchecked_value' => Status::STATUS_INACTIVE
                ),
            ));
        }
        $this->add(array(
            'name' => 'id',
            'type' => 'Zend\Form\Element\Hidden',
        ));

        $this->add(array(
            'name' => 'Create',
            'type' => 'Zend\Form\Element\Submit',
            'attributes' => array(
                'class' => 'btn btn-success',
                'value' => 'Create',
            )
        ));

        $this->add(array(
            'name' => 'reset',
            'type' => 'Zend\Form\Element',
            'attributes' => array(
                'class' => 'btn btn-danger resetButton',
                'value' => 'Reset',
                'type' => 'button',
            )
        ));
    }

}
