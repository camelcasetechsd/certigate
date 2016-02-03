<?php

namespace DefaultModule\Form;

use Utilities\Form\Form;
use Zend\InputFilter\InputFilter;

/**
 * ContactUs Form
 * 
 * Handles Contact us form setup and validation
 * 
 * 
 * 
 * @property InputFilter $_inputFilter validation constraints 
 * @package defaultModule
 * @subpackage form
 */
class ContactUsForm extends Form {

    /**
     *
     * @var InputFilter validation constraints 
     */
    private $_inputFilter;

    /**
     * setup form and add validation constraints
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
                'placeholder' => 'Enter text here',
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => 'Name',
            ),
        ));
        
        $this->add(array(
            'name' => 'email',
            'type' => 'Zend\Form\Element\Email',
            'attributes' => array(
                'required' => 'required',
                'placeholder' => 'Enter email address',
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => 'Email',
            ),
        ));

        $this->add(array(
            'name' => 'subject',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
                'required' => 'required',
                'placeholder' => 'Enter text here',
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => 'Subject',
            ),
        ));
        
        $this->add(array(
            'name' => 'message',
            'type' => 'Zend\Form\Element\Textarea',
            'attributes' => array(
                'required' => 'required',
                'placeholder' => 'Enter your comment here',
                'class' => 'form-control',
                'rows' => 5,
            ),
            'options' => array(
                'label' => 'Message',
            ),
        ));

        $this->add(array(
            'name' => 'submit',
            'type' => 'Zend\Form\Element\Submit',
            'attributes' => array(
                'class' => 'btn btn-lg btn-success btn-block',
                'value' => 'Submit',
            )
        ));

        $this->setInputFilter($this->getInputFilter());
    }

    /**
     * set validation constraints
     * 
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
                'name' => 'name',
                'required' => true,
            ));
            $inputFilter->add(array(
                'name' => 'email',
                'required' => true,
            ));
            $inputFilter->add(array(
                'name' => 'subject',
                'required' => true,
            ));
            $inputFilter->add(array(
                'name' => 'message',
                'required' => true,
            ));

            $this->_inputFilter = $inputFilter;
        }

        return $this->_inputFilter;
    }

}
