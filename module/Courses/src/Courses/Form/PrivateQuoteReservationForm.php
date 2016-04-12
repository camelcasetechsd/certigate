<?php

namespace Courses\Form;

use Utilities\Form\Form;
use Utilities\Service\Time;
use Utilities\Form\FormButtons;
use Zend\InputFilter\InputFilter;

/**
 * PrivateQuoteReservation Form
 * 
 * Handles PrivateQuote Reservation
 * 
 * @property Utilities\Service\Query\Query $query
 * @property InputFilter $_inputFilter validation constraints 
 * @package courses
 * @subpackage form
 */
class PrivateQuoteReservationForm extends Form
{

    /**
     *
     * @var Utilities\Service\Query\Query
     */
    protected $query;
    
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
        $this->query = $options['query'];
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
            'name' => 'course',
            'type' => 'Zend\Form\Element\Hidden',
            'attributes' => array(
                'value' => $options["course"],
            ),
        ));
        
        $this->add(array(
            'name' => 'venue',
            'type' => 'DoctrineModule\Form\Element\ObjectSelect',
            'attributes' => array(
                'required' => 'required',
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => 'Venue',
                'object_manager' => $this->query->entityManager,
                'target_class' => 'Courses\Entity\PrivateQuoteVenue',
                'property' => 'name',
                'is_method' => false,
                'find_method' => array(
                    'name' => 'findAll',
                    'params' => array(
                    )
                ),
                'empty_item_label' => Form::EMPTY_SELECT_VALUE,
                'display_empty_item' => true,
            ),
        ));
        
        $this->add(array(
            'name' => 'preferredDate',
            'type' => 'Zend\Form\Element\Date',
            'attributes' => array(
                'required' => 'required',
                'class' => 'form-control date',
                'type' => 'text',
            ),
            'options' => array(
                'label' => 'Preferred Date',
                'format' => Time::DATE_FORMAT,
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
                'name' => 'course',
                'required' => true,
            ));
            
            $inputFilter->add(array(
                'name' => 'venue',
                'required' => true,
            ));

            $inputFilter->add(array(
                'name' => 'preferredDate',
                'required' => true,
            ));

            $this->_inputFilter = $inputFilter;
        }

        return $this->_inputFilter;
    }
}
