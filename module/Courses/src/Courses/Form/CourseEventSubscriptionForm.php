<?php

namespace Courses\Form;

use Utilities\Form\Form;
use Utilities\Form\FormButtons;

/**
 * CourseEventSubscriptionForm Form
 * 
 * Handles CourseEventSubscription form setup
 * 
 * 
 * @package courses
 * @subpackage form
 */
class CourseEventSubscriptionForm extends Form
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

        $this->setAttribute('class', 'form form-inline');
        $this->setAttribute('action', '/course-event-subscription/alert-subscribe/'.$options["courseEventId"]);

        $this->add(array(
            'name' => 'user',
            'type' => 'Zend\Form\Element\Hidden',
        ));

        $this->add(array(
            'name' => 'courseEvent',
            'type' => 'Zend\Form\Element\Hidden',
        ));

        $this->add(array(
            'name' => 'id',
            'type' => 'Zend\Form\Element\Hidden',
        ));

        if ($options["isSubscribed"] === true) {
            $buttonName = FormButtons::UNSUBSCRIBE_BUTTON;
            $buttonValue = "Unsubscribe periodic notifications";
        }
        else {
            $buttonName = FormButtons::SUBSCRIBE_BUTTON;
            $buttonValue = "Subscribe periodic notifications";
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
