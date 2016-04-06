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
 * @property Translation\Helper\TranslatorHelper $translatorHandler
 * @property Courses\Model\PrivateQuote $privateQuoteModel
 * @property InputFilter $_inputFilter validation constraints 
 * @package courses
 * @subpackage form
 */
class PrivateQuoteReservationForm extends Form
{

    /**
     *
     * @var Translation\Helper\TranslatorHelper
     */
    protected $translatorHandler;
    /**
     *
     * @var Courses\Model\PrivateQuote
     */
    protected $privateQuoteModel;
    
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
        $this->translatorHandler = $options['translatorHandler'];
        $this->privateQuoteModel = $options['privateQuoteModel'];
        unset($options['translatorHandler']);
        unset($options['privateQuoteModel']);
        
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
            'type' => 'Zend\Form\Element\Select',
            'attributes' => array(
                'class' => 'form-control',
                'required' => 'required',
            ),
            'options' => array(
                'label' => 'Venue',
                'value_options' => $this->privateQuoteModel->getTranslatedVenueTypes(),
                'empty_option' => $this->translatorHandler->translate(self::EMPTY_SELECT_VALUE),
            )
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
