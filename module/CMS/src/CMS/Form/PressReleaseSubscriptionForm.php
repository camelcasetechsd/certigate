<?php

namespace CMS\Form;

use Utilities\Form\Form;
use Utilities\Form\FormButtons;

/**
 * PressReleaseSubscription Form
 * 
 * Handles PressReleaseSubscription form setup
 * 
 * 
 * @package cms
 * @subpackage form
 */
class PressReleaseSubscriptionForm extends Form
{

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
        parent::__construct($name, $options);

        $this->setAttribute('class', 'form form-horizontal');
        $this->setAttribute('action', $options["action"]);

        $this->add(array(
            'name' => 'id',
            'type' => 'Zend\Form\Element\Hidden',
        ));

        if ($options["isSubscribed"] === true) {
            $buttonName = FormButtons::UNSUBSCRIBE_BUTTON;
            $buttonValue = FormButtons::UNSUBSCRIBE_BUTTON_TEXT;
        }
        else {
            $buttonName = FormButtons::SUBSCRIBE_BUTTON;
            $buttonValue = FormButtons::SUBSCRIBE_BUTTON_TEXT;
        }
        $this->add(array(
            'name' => $buttonName,
            'type' => 'Zend\Form\Element\Submit',
            'attributes' => array(
                'class' => 'btn btn-success',
                'value' => $buttonValue,
            )
        ));
    }

}
