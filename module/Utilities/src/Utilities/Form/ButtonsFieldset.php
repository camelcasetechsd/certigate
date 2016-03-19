<?php

namespace Utilities\Form;

use Utilities\Form\FormButtons;
use Zend\Form\Fieldset;

/**
 * Buttons Fieldset
 * 
 * Handles submission and reset buttons setup
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
     * 
     * @param string $name ,default is null
     * @param array $options ,default is empty array
     */
    public function __construct($name = null, $options = array())
    {
        parent::__construct(/* $name = */ (!empty($name)) ? $name : "buttons", $options);

        if (array_key_exists("create_button_only", $options) && $options["create_button_only"] === true) {
            $this->add(array(
                'name' => FormButtons::CREATE_BUTTON,
                'type' => 'Zend\Form\Element\Submit',
                'attributes' => array(
                    'class' => 'pull-left btn-inline btn btn-success',
                    'value' => FormButtons::CREATE_BUTTON_TEXT,
                )
            ));
        }
        else {
            $this->add(array(
                'name' => FormButtons::SAVE_BUTTON,
                'type' => 'Zend\Form\Element\Submit',
                'attributes' => array(
                    'class' => 'pull-left btn-inline btn btn-primary',
                    'value' => FormButtons::SAVE_BUTTON_TEXT,
                )
            ));

            $this->add(array(
                'name' => FormButtons::SAVE_AND_PUBLISH_BUTTON,
                'type' => 'Zend\Form\Element\Submit',
                'attributes' => array(
                    'class' => 'pull-left btn-inline btn btn-success',
                    'value' => FormButtons::SAVE_AND_PUBLISH_BUTTON_TEXT,
                )
            ));

            $this->add(array(
                'name' => FormButtons::UNPUBLISH_BUTTON,
                'type' => 'Zend\Form\Element\Submit',
                'attributes' => array(
                    'class' => 'pull-left btn-inline btn btn-warning',
                    'value' => FormButtons::UNPUBLISH_BUTTON_TEXT,
                )
            ));
        }

        $this->add(array(
            'name' => FormButtons::RESET_BUTTON,
            'type' => 'Zend\Form\Element',
            'attributes' => array(
                'class' => 'pull-left btn-inline btn btn-danger resetButton',
                'value' => FormButtons::RESET_BUTTON_TEXT,
                'type' => 'button',
            )
        ));
    }

}
