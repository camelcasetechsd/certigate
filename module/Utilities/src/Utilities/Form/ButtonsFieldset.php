<?php

namespace Utilities\Form;

use Utilities\Form\FormButtons;
use Zend\Form\Fieldset;

/**
 * Buttons Fieldset
 * 
 * Handles Outline form setup
 * 
 * @package utilities
 * @subpackage form
 */
class ButtonsFieldset extends Fieldset
{
    
    /**
     * setup form
     * 
     * @access public
     */
    public function __construct()
    {
        parent::__construct(/*$name =*/ "buttons");
        
        $this->add( array(
            'name' => FormButtons::SAVE_BUTTON,
            'type' => 'Zend\Form\Element\Submit',
            'attributes' => array(
                'class' => 'pull-left btn-inline btn btn-primary',
                'value' => FormButtons::SAVE_BUTTON_TEXT,
            )
        ) );
        
        $this->add( array(
            'name' => FormButtons::SAVE_AND_PUBLISH_BUTTON,
            'type' => 'Zend\Form\Element\Submit',
            'attributes' => array(
                'class' => 'pull-left btn-inline btn btn-success',
                'value' => FormButtons::SAVE_AND_PUBLISH_BUTTON_TEXT,
            )
        ) );
        
        $this->add( array(
            'name' => FormButtons::UNPUBLISH_BUTTON,
            'type' => 'Zend\Form\Element\Submit',
            'attributes' => array(
                'class' => 'pull-left btn-inline btn btn-warning',
                'value' => FormButtons::UNPUBLISH_BUTTON_TEXT,
            )
        ) );

        $this->add( array(
            'name' => FormButtons::RESET_BUTTON,
            'type' => 'Zend\Form\Element',
            'attributes' => array(
                'class' => 'pull-left btn-inline btn btn-danger resetButton',
                'value' => FormButtons::RESET_BUTTON_TEXT,
                'type' => 'button',
            )
        ) );
    }
}
