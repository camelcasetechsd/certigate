<?php

namespace Courses\Form;

use Utilities\Form\Form;
use Utilities\Service\Status;
use Users\Entity\Role;

/**
 * Course Form
 * 
 * Handles Course form setup
 * 
 * @property Utilities\Service\Query\Query $query
 * @property bool $isAdminUser
 * 
 * @package courses
 * @subpackage form
 */
class CourseForm extends Form {

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
     * setup form
     * 
     * 
     * @access public
     * @param string $name ,default is null
     * @param array $options ,default is null
     */
    public function __construct($name = null, $options = null) {
        $this->query = $options['query'];
        unset($options['query']);
        $this->isAdminUser = $options['isAdminUser'];
        unset($options['isAdminUser']);
        parent::__construct($name, $options);

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

        $this->add(array(
            'name' => 'startDate',
            'type' => 'Zend\Form\Element\Date',
            'attributes' => array(
                'placeholder' => 'Example: 10/10/2010',
                'required' => 'required',
                'class' => 'form-control date',
                'type' => 'text',
            ),
            'options' => array(
                'label' => 'Start Date',
                'format' => 'm/d/Y',
            ),
        ));
        $this->add(array(
            'name' => 'endDate',
            'type' => 'Zend\Form\Element\Date',
            'attributes' => array(
                'placeholder' => 'Example: 10/10/2010',
                'required' => 'required',
                'class' => 'form-control date',
                'type' => 'text',
            ),
            'options' => array(
                'label' => 'End Date',
                'format' => 'm/d/Y',
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

        $this->add(array(
            'name' => 'atp',
            'type' => 'DoctrineModule\Form\Element\ObjectSelect',
            'attributes' => array(
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => 'Authorized Training Partner',
                'object_manager' => $this->query->entityManager,
                'target_class' => 'Organizations\Entity\Organization',
                'property' => 'commercialName',
                'is_method' => false,
                'find_method' => array(
                    'name' => 'findAll',
                    'params' => array(
                    )
                ),
            ),
        ));
        $this->add(array(
            'name' => 'ai',
            'type' => 'DoctrineModule\Form\Element\ObjectSelect',
            'attributes' => array(
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
                        'criteria' => array(
                            "roles" => array(
                                Role::INSTRUCTOR_ROLE
                            )
                        )
                    )
                ),
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
