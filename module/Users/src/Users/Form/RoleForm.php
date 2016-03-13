<?php

namespace Users\Form;

use Utilities\Form\Form;
use Utilities\Form\ButtonsFieldset;

/**
 * Role Form
 * 
 * Handles Role form setup
 * 
 * 
 * @package users
 * @subpackage form
 */
class RoleForm extends Form {
    
    /**
     * setup form
     * 
     * 
     * @access public
     * @param string $name ,default is null
     * @param array $options ,default is null
     */
    public function __construct($name = null, $options = null) {
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
            'name' => 'id',
            'type' => 'Zend\Form\Element\Hidden',
        ));

        // Add buttons fieldset
        $buttonsFieldset = new ButtonsFieldset(/*$name =*/ null, /*$options =*/ array("create_button_only" => true));
        $this->add($buttonsFieldset);
    }

}
