<?php

namespace Courses\Form;

use Utilities\Form\Form;
use Utilities\Service\Status;
use CustomDoctrine\Service\DoctrineObject as DoctrineHydrator;
use Courses\Form\OutlineFieldset;

/**
 * Course Form
 * 
 * Handles Course form setup
 * 
 * @property Utilities\Service\Query\Query $query
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
                'label' => 'Duration (days)',
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
                    'label' => 'CIP',
                    'checked_value' => Status::STATUS_ACTIVE,
                    'unchecked_value' => Status::STATUS_INACTIVE
                ),
            ));
        }
        
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
            'name' => 'id',
            'type' => 'Zend\Form\Element\Hidden',
        ));

        // Add buttons fieldset
        $this->add(array(
             'name' => 'buttons',
             'type' => 'Utilities\Form\ButtonsFieldset'
         ));
    }

}
