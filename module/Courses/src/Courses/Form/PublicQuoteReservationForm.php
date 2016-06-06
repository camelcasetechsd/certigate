<?php

namespace Courses\Form;

use Utilities\Form\Form;
use Utilities\Form\FormButtons;
use Zend\InputFilter\InputFilter;

/**
 * PublicQuoteReservation Form
 * 
 * Handles PublicQuote Reservation
 * 
 * @property InputFilter $_inputFilter validation constraints 
 * @package courses
 * @subpackage form
 */
class PublicQuoteReservationForm extends Form
{

    /**
     *
     * @var InputFilter validation constraints 
     */
    private $_inputFilter;
    
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
            'name' => 'user',
            'type' => 'Zend\Form\Element\Hidden',
            'attributes' => array(
                'value' => $options["user"],
            ),
        ));
        
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
                'min' => '1',
            ),
            'options' => array(
                'label' => 'Seats No',
            ),
        ));

        $this->add(array(
            'name' => FormButtons::SAVE_BUTTON,
            'type' => 'Zend\Form\Element\Submit',
            'attributes' => array(
                'class' => 'btn btn-success',
                'value' => FormButtons::SAVE_BUTTON_TEXT,
            )
        ));
        
        $this->setInputFilter($this->getInputFilter());
    }
    
    /**
     * set validation constraints
     * 
     * @uses InputFilter
     * 
     * @access public
     * @return InputFilter validation constraints
     */
    public function getInputFilter() {
        if (!$this->_inputFilter) {
            $inputFilter = new InputFilter();

            $inputFilter->add(array(
                'name' => 'user',
                'required' => true,
            ));

            $inputFilter->add(array(
                'name' => 'courseEvent',
                'required' => true,
            ));

            $inputFilter->add(array(
                'name' => 'seatsNo',
                'required' => true,
            ));

            $this->_inputFilter = $inputFilter;
        }

        return $this->_inputFilter;
    }

}
