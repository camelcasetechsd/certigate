<?php

namespace Users\Form;

use Zend\Form\Fieldset;
use Users\Service\Statement;

/**
 * Agreements Fieldset
 * 
 * Handles agreements setup
 * 
 * @package users
 * @subpackage form
 */
class AgreementsFieldset extends Fieldset
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
        parent::__construct(/* $name = */ (!empty($name)) ? $name : "agreements", $options);

        $this->add(array(
            'name' => 'instructorStatement',
            'type' => 'Zend\Form\Element\Checkbox',
            'attributes' => array(
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => '',
                'checked_value' => Statement::STATEMENT_AGREE,
                'unchecked_value' => Statement::STATEMENT_DISAGREE
            ),
        ));

        $this->add(array(
            'name' => 'proctorStatement',
            'type' => 'Zend\Form\Element\Checkbox',
            'attributes' => array(
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => '',
                'checked_value' => Statement::STATEMENT_AGREE,
                'unchecked_value' => Statement::STATEMENT_DISAGREE
            ),
        ));

        $this->add(array(
            'name' => 'studentStatement',
            'type' => 'Zend\Form\Element\Checkbox',
            'attributes' => array(
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => '',
                'checked_value' => Statement::STATEMENT_AGREE,
                'unchecked_value' => Statement::STATEMENT_DISAGREE
            ),
        ));

        $this->add(array(
            'name' => 'testCenterAdministratorStatement',
            'type' => 'Zend\Form\Element\Checkbox',
            'attributes' => array(
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => '',
                'checked_value' => Statement::STATEMENT_AGREE,
                'unchecked_value' => Statement::STATEMENT_DISAGREE
            ),
        ));

        $this->add(array(
            'name' => 'trainingManagerStatement',
            'type' => 'Zend\Form\Element\Checkbox',
            'attributes' => array(
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => '',
                'checked_value' => Statement::STATEMENT_AGREE,
                'unchecked_value' => Statement::STATEMENT_DISAGREE
            ),
        ));
    }

}
