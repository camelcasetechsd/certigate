<?php

namespace IssueTracker\Form;

use Utilities\Form\Form;
use IssueTracker\Service\DepthLevel;
use Utilities\Form\FormButtons;

class IssuesCategoriesForm extends Form
{

    protected $query;

    public function __construct($name = null, $options = null)
    {
        $this->query = $options['query'];

        unset($options['query']);
        parent::__construct($name, $options);

        $this->setAttribute('class', 'form form-horizontal');

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
            'name' => 'description',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
                'required' => 'required',
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => 'Description',
            ),
        ));

        $this->add(array(
            'name' => 'weight',
            'type' => 'Zend\Form\Element\Number',
            'attributes' => array(
                'placeholder' => 'Lower value will be displayed at the Top',
                'required' => 'required',
                'class' => 'form-control',
                'min' => '1',
            ),
            'options' => array(
                'label' => 'Sort Order',
            ),
        ));

        $this->add(array(
            'name' => 'parent',
            'type' => 'DoctrineModule\Form\Element\ObjectSelect',
            'attributes' => array(
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => 'Parent',
                'object_manager' => $this->query->entityManager,
                'target_class' => 'IssueTracker\Entity\IssueCategory',
                'display_empty_item' => true,
                'empty_item_label' => "#",
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
                'type' => 'button',
                'value' => FormButtons::RESET_BUTTON_TEXT,
            )
        ));
    }

}
