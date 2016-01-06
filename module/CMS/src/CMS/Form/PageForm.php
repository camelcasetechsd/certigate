<?php

namespace CMS\Form;

use Utilities\Form\Form;
use Utilities\Service\Status;

/**
 * Page Form
 * 
 * Handles Page form setup
 * 
 * @property Utilities\Service\Query\Query $query
 * 
 * @package cms
 * @subpackage form
 */
class PageForm extends Form {

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
    public function __construct($name = null, $options = null) {
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
            'name' => 'body',
            'type' => 'Zend\Form\Element\Textarea',
            'attributes' => array(
                'required' => 'required',
                'class' => 'form-control',
                'rows' => 10,
                'cols' => 80,
            ),
            'options' => array(
                'label' => 'Body',
            ),
        ));

        $this->add(array(
            'name' => 'menuItem',
            'type' => 'DoctrineModule\Form\Element\ObjectSelect',
            'attributes' => array(
                'required' => 'required',
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => 'Menu Item',
                'object_manager' => $this->query->entityManager,
                'target_class' => 'CMS\Entity\MenuItem',
                'label_generator' => function($targetEntity) {
                    return $targetEntity->getNestedTitle();
                },
                'find_method' => array(
                    'name' => 'getMenuItemsSorted',
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
