<?php

namespace Organizations\Form;

use Utilities\Form\Form;

/**
 * User Form
 * 
 * Handles User form setup
 * 
 * 
 * @property Utilities\Service\Query\Query $query
 * 
 * @package org
 * @subpackage form
 */
class TypeForm extends Form
{

    protected $objectManager;

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
//        $excludedRoles = $options['excludedRoles'];
        unset($options['query']);
//        unset($options['countries']);
//        unset($options['languages']);
//        unset($options['excludedRoles']);
        parent::__construct($name, $options);

        $this->setAttribute('class', 'form form-horizontal');

        $this->add(array(
            'name' => 'type',
            'required' => true,
            'type' => 'Zend\Form\Element\Hidden',
            'options' => array(
                'messages' => array(
                    \Zend\Validator\NotEmpty::IS_EMPTY => "you have to choose organization type"
                )
            )
        ));

        
        $this->add(array(
            'type' => 'Zend\Form\Element\MultiCheckbox',
            'name' => 'orgType',
            'required' => true,
            'options' => array(
                'label' => 'Organization Type',
                'value_options' => array(
                    array(
                        'value' => '1',
                        'label' => '    ATC Organization',
                        'checked' => false,
                        'attributes' => array(
                            'class' => 'orgType',
                            'id' => 'type-1',
                        ),
                    ),
                    array(
                        'value' => '2',
                        'label' => '    ATP Organization',
                        'checked' => false,
                        'attributes' => array(
                            'id' => 'type-2',
                            'class' => 'orgType',
                        ),
                    ),
                ),
            ),
        ));


        $this->add(array(
            'name' => 'submit',
            'type' => 'Zend\Form\Element\Submit',
            'attributes' => array(
                'class' => 'btn btn-success',
                'value' => 'Start!',
            )
        ));
    }

}
