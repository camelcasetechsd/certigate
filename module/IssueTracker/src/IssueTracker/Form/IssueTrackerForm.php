<?php

namespace IssueTracker\Form;

use Utilities\Form\Form;
use Utilities\Service\Status;
use Utilities\Form\FormButtons;

/**
 * MenuItem Form
 * 
 * Handles MenuItem form setup
 * 
 * @property Utilities\Service\Query\Query $query
 * 
 * @package cms
 * @subpackage form
 */
class IssueTrackerForm extends Form
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
        $this->query = $options['query'];
        unset($options['query']);
        parent::__construct($name, $options);

        $this->setAttribute('class', 'form form-horizontal');
        $this->setAttribute('enctype', 'multipart/form-data');

        $this->add(array(
            'name' => 'parent',
            'type' => 'DoctrineModule\Form\Element\ObjectSelect',
            'attributes' => array(
                'class' => 'form-control',
                'required' => true,
            ),
            'options' => array(
                'label' => 'Category',
                'object_manager' => $this->query->entityManager,
                'target_class' => 'IssueTracker\Entity\IssueCategory',
                'display_empty_item' => true,
                'empty_item_label' => "-- Select --",
                'label_generator' => function($targetEntity) {
                    return $targetEntity->getTitle();
                },
                'find_method' => array(
                    'name' => 'getCategoriesSorted',
                    'params' => array(
                    )
                ),
            ),
        ));

        $this->add(array(
            'name' => 'title',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
                'required' => 'required',
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => 'Title',
            ),
        ));

        $this->add(array(
            'name' => 'filePath',
            'type' => 'Zend\Form\Element\File',
            'attributes' => array(
                'multiple' => true
            ),
            'options' => array(
                'label' => 'File',
                'label_options' => array(
                    'disable_html_escape' => true,
                )
            ),
        ));

        $this->add(array(
            'name' => 'description',
            'type' => 'Zend\Form\Element\Textarea',
            'attributes' => array(
                'required' => 'required',
                'class' => 'form-control',
                'rows' => 3,
                'cols' => 100,
            ),
            'options' => array(
                'label' => 'Description',
            ),
        ));

        $this->add(array(
            'name' => 'id',
            'type' => 'Zend\Form\Element\Hidden',
        ));

        $this->add(array(
            'name' => FormButtons::SAVE_BUTTON,
            'type' => 'Zend\Form\Element\Submit',
            'attributes' => array(
                'class' => 'btn btn-success',
                'value' => FormButtons::SAVE_BUTTON_TEXT,
            )
        ));

        $this->add(array(
            'name' => FormButtons::RESET_BUTTON,
            'type' => 'Zend\Form\Element',
            'attributes' => array(
                'class' => 'btn btn-danger resetButton',
                'value' => 'Reset',
                'type' => FormButtons::RESET_BUTTON_TEXT,
            )
        ));
    }

}
