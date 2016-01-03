<?php

namespace DefaultModule\Form;

use Utilities\Form\Form;
use Zend\InputFilter\InputFilter;

/**
 * Signin Form
 * 
 * Handles Authentication form setup and validation
 * 
 * 
 * 
 * @property InputFilter $_inputFilter validation constraints 
 * @package defaultModule
 * @subpackage form
 */
class SigninForm extends Form {

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
            'name' => 'username',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
                'required' => 'required',
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => 'Username',
            ),
        ));

        $this->add(array(
            'name' => 'password',
            'type' => 'Zend\Form\Element\Password',
            'attributes' => array(
                'required' => 'required',
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => 'Password',
            ),
        ));

        $this->add(array(
            'name' => 'login',
            'type' => 'Zend\Form\Element\Submit',
            'attributes' => array(
                'class' => 'btn btn-lg btn-success btn-block',
                'value' => 'Log in',
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
                'name' => 'password',
                'required' => true,
                'filters' => array(
                    array(
                        'name' => 'StringTrim',
                    )
                )
            ));

            $inputFilter->add(array(
                'name' => 'username',
                'required' => true,
                'filters' => array(
                    array(
                        'name' => 'StringTrim',
                    )
                ),
                'validators' => array(
                    array('name' => 'stringLength',
                        'options' => array(
                            'min' => 1,
                            'max' => 25,
                        )
                    ),
                )
            ));

            $this->_inputFilter = $inputFilter;
        }

        return $this->_inputFilter;
    }

}
