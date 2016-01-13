<?php

namespace Users\Form;

use Utilities\Form\Form;
use Zend\Form\FormInterface;

/**
 * User Form
 * 
 * Handles User form setup
 * 
 * 
 * @property Utilities\Service\Query\Query $query
 * 
 * @package users
 * @subpackage form
 */
class UserForm extends Form
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
    public function __construct( $name = null, $options = null )
    {
        $this->query = $options['query'];
        $countries = $options['countries'];
        $languages = $options['languages'];
        unset( $options['query'] );
        unset( $options['countries'] );
        unset( $options['languages'] );
        parent::__construct( $name, $options );

        $this->setAttribute( 'class', 'form form-horizontal' );

        $this->add( array(
            'name' => 'firstName',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
                'placeholder' => 'Enter User\'s first name',
                'required' => 'required',
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => 'First Name',
            ),
        ) );
        $this->add( array(
            'name' => 'middleName',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
                'placeholder' => 'Enter User\'s first name',
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => 'Middle Name',
            ),
        ) );
        $this->add( array(
            'name' => 'lastName',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
                'placeholder' => 'Enter User\'s first name',
                'required' => 'required',
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => 'Last Name',
            ),
        ) );
        
        $this->add(array(
            'name' => 'country',
            'type' => 'Zend\Form\Element\Select',
            'attributes' => array(
                'class' => 'form-control',
                'required' => 'required',
            ),
            'options' => array(
                'label' => 'Country',
                'value_options' => $countries,
            ),
        ));
        
        $this->add(array(
            'name' => 'language',
            'type' => 'Zend\Form\Element\Select',
            'attributes' => array(
                'class' => 'form-control',
                'required' => 'required',
            ),
            'options' => array(
                'label' => 'Language',
                'value_options' => $languages,
            ),
        ));
        
        $this->add( array(
            'name' => 'dateOfBirth',
            'type' => 'Zend\Form\Element\Date',
            'attributes' => array(
                'placeholder' => 'Example: 10/10/2010',
                'required' => 'required',
                'class' => 'form-control date',
                'type' => 'text',
            ),
            'options' => array(
                'label' => 'Date Of Birth',
                'format' => 'm/d/Y',
            ),
        ) );
        
        $this->add( array(
            'name' => 'username',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
                'placeholder' => 'Enter User Name',
                'required' => 'required',
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => 'User Name',
            ),
        ) );

        $this->add( array(
            'name' => 'password',
            'type' => 'Zend\Form\Element\Password',
            'attributes' => array(
                'placeholder' => 'Enter User Password',
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => 'Password',
            ),
        ) );

        $this->add( array(
            'name' => 'confirmPassword',
            'type' => 'Zend\Form\Element\Password',
            'attributes' => array(
                'placeholder' => 'Confirm User Password',
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => 'Confirm Password',
            ),
        ) );
        

        $this->add(array(
            'name' => 'roles',
            'type' => 'DoctrineModule\Form\Element\ObjectSelect',
            'attributes' => array(
                'class' => 'form-control',
                'multiple' => true,
            ),
            'options' => array(
                'label' => 'Roles',
                'object_manager' => $this->query->entityManager,
                'target_class' => 'Users\Entity\Role',
                'property' => 'name',
                'find_method' => array(
                    'name' => 'findAll',
                    'params' => array(
                    )
                )
            ),
        ));
        
        $this->add( array(
            'name' => 'mobile',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
                'placeholder' => 'Enter User Mobile #',
                'required' => 'required',
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => 'Mobile',
            ),
        ) );

        $this->add( array(
            'name' => 'photo',
            'type' => 'Zend\Form\Element\File',
            'options' => array(
                'label' => 'Picture',
            ),
        ) );
        $this->add( array(
            'name' => 'id',
            'type' => 'Zend\Form\Element\Hidden',
        ) );

        $this->add( array(
            'name' => 'submit',
            'type' => 'Zend\Form\Element\Submit',
            'attributes' => array(
                'class' => 'btn btn-success',
                'value' => 'Create',
            )
        ) );
        $this->add( array(
            'name' => 'reset',
            'type' => 'Zend\Form\Element',
            'attributes' => array(
                'class' => 'btn btn-danger resetButton',
                'value' => 'Reset',
                'type' => 'button',
            )
        ) );
    }

    public function bind( $object, $flags = FormInterface::VALUES_NORMALIZED)
    {
        parent::bind( $object, $flags );
        
        $userRoles = $object->getRoles();
        
        $userRolesValues = [];
        foreach ($userRoles as $r){
            $userRolesValues[] = $r->getId();
        }
        $this->get('roles')->setValue($userRolesValues);
    }

}
