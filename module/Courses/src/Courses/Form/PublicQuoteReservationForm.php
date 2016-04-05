<?php

namespace Courses\Form;

use Utilities\Form\Form;
use Utilities\Form\FormButtons;

/**
 * PublicQuoteReservation Form
 * 
 * Handles PublicQuote Reservation
 * 
 * @package courses
 * @subpackage form
 */
class PublicQuoteReservationForm extends Form
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
        $this->setAttribute('action', $options["actionUrl"]);

        $this->add(array(
            'name' => 'courseEvent',
            'type' => 'Zend\Form\Element\Hidden',
            'attributes' => array(
                'value' => $options["courseEvent"],
            ),
        ));
        
        $this->add(array(
            'name' => 'seatsNo',
            'type' => 'Zend\Form\Element\Number',
            'attributes' => array(
                'required' => 'required',
                'class' => 'form-control',
                'min' => '0',
            ),
            'options' => array(
                'label' => 'Seats Number',
            ),
        ));

        $this->add(array(
            'name' => FormButtons::RESERVE_BUTTON,
            'type' => 'Zend\Form\Element\Submit',
            'attributes' => array(
                'class' => 'btn btn-success',
                'value' => FormButtons::RESERVE_BUTTON_TEXT,
            )
        ));
    }

}
