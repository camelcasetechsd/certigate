<?php

namespace Courses\Form;

use Utilities\Form\Form;
use Utilities\Service\Status;
use Courses\Entity\Resource;

/**
 * Resource Form
 * 
 * Handles Resource form setup
 * 
 * @property Utilities\Service\Query\Query $query
 * @property bool $isAdminUser
 * 
 * @package courses
 * @subpackage form
 */
class ResourceForm extends Form {

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

        $types = array(
            Resource::TYPE_PRESENTATIONS,
            Resource::TYPE_ACTIVITIES,
            Resource::TYPE_EXAMS,
        );
        $typeValueOptions = array_combine( /* $keys =*/ $types , /* $values =*/ $types );
        $this->add(array(
            'name' => 'type',
            'type' => 'Zend\Form\Element\Select',
            'attributes' => array(
                'class' => 'form-control',
                'required' => 'required',
            ),
            'options' => array(
                'label' => 'Type',
                'value_options' => $typeValueOptions,
            ),
        ));
        
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
            'name' => 'course',
            'type' => 'DoctrineModule\Form\Element\ObjectSelect',
            'attributes' => array(
                'class' => 'form-control',
                'required' => 'required',
            ),
            'options' => array(
                'label' => 'Course',
                'object_manager' => $this->query->entityManager,
                'target_class' => 'Courses\Entity\Course',
                'property' => 'name',
                'is_method' => false,
                'find_method' => array(
                    'name' => 'findAll',
                    'params' => array(
                    )
                ),
            ),
        ));
        
        $this->add(array(
            'name' => 'file',
            'type' => 'Zend\Form\Element\File',
            'attributes' => array(
            ),
            'options' => array(
                'label' => 'File',
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
