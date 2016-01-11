<?php

namespace Directories\Form;

use Utilities\Form\Form;

/**
 * Atc Form
 * 
 * Handles Atc form setup
 * 
 * 
 * @property Utilities\Service\Query\Query $query
 * 
 * @package directories
 * @subpackage form
 */
class AtcForm extends Form
{

    /**
     *
     * @var Utilities\Service\Query\Query 
     */
    protected $query;

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
        unset($options['query']);
        parent::__construct($name, $options);

        $this->setAttribute('class', 'form form-horizontal');

        $this->add(array(
            'name' => 'name',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
                'placeholder' => 'Enter Atc\'s Name',
                'required' => 'required',
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => 'Atc\'s Name: ',
            ),
        ));

        $this->add(array(
            'name' => 'telephone',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
                'placeholder' => 'Enter Atc\'s telephone',
                'required' => 'required',
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => 'Telephone: ',
            ),
        ));


        $this->add(array(
            'name' => 'description',
            'type' => 'Zend\Form\Element\Textarea',
            'attributes' => array(
                'class' => 'form-control',
                'rows' => '5',
                'placeholder' => 'Enter Atc\'s description'
            ),
            'options' => array(
                'label' => 'Description: ',
            ),
        ));
        
        $this->add(array(
            'name' => 'Address',
            'type' => 'Zend\Form\Element\Textarea',
            'attributes' => array(
                'class' => 'form-control',
                'placeholder' => 'Enter Atc\'s address'
            ),
            'options' => array(
                'label' => 'Address: ',
            ),
        ));
        
        $this->add(array(
            'name' => 'email',
            'type' => 'Zend\Form\Element\Textarea',
            'attributes' => array(
                'class' => 'form-control',
                'placeholder' => 'Enter Atc\'s email'
            ),
            'options' => array(
                'label' => 'Email: ',
            ),
        ));

        $this->add(array(
            'name' => 'id',
            'type' => 'Zend\Form\Element\Hidden',
        ));

        $this->add(array(
            'name' => 'submit',
            'type' => 'Zend\Form\Element\Submit',
            'attributes' => array(
                'class' => 'btn btn-success',
                'value' => 'Create',
            )
        ));
        
        $this->add(array(
            'name' => 'reset',
            'type' => 'Zend\Form\Element',
            'attributes' => array(
                'class' => 'btn btn-danger resetButton',
                'value' => 'Reset',
                'type' => 'button',
            )
        ));
    }

}
