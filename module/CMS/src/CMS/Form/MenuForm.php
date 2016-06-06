<?php

namespace CMS\Form;

use Utilities\Form\Form;
use Utilities\Service\Status;
use Utilities\Form\ButtonsFieldset;

/**
 * Menu Form
 * 
 * Handles Menu form setup
 * 
 * 
 * @package cms
 * @subpackage form
 */
class MenuForm extends Form {
    
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
            'name' => 'titleAr',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
                'required' => 'required',
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => 'Title in Arabic',
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
        
        $this->add(array(
            'name' => 'id',
            'type' => 'Zend\Form\Element\Hidden',
        ));

        // Add buttons fieldset
        $buttonsFieldset = new ButtonsFieldset(/*$name =*/ null, /*$options =*/ array("save_button_only" => true));
        $this->add($buttonsFieldset);
    }

}
