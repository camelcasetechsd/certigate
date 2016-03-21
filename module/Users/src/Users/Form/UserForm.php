<?php

namespace Users\Form;

use Utilities\Form\Form;
use Users\Service\Statement;
use Utilities\Service\Time;
use Utilities\Form\ButtonsFieldset;

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
    public function __construct($name = null, $options = null)
    {
        $this->query = $options['query'];
        $countries = $options['countries'];
        $languages = $options['languages'];
        $excludedRoles = $options['excludedRoles'];
        unset($options['query']);
        unset($options['countries']);
        unset($options['languages']);
        unset($options['excludedRoles']);
        parent::__construct($name, $options);

        $this->setAttribute('class', 'form form-horizontal');

        $this->add(array(
            'name' => 'firstName',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
                'placeholder' => 'Enter First Name',
                'required' => 'required',
                'class' => 'form-register',
            ),
            'options' => array(
                'label' => 'First Name',
            ),
        ));
        $this->add(array(
            'name' => 'middleName',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
                'placeholder' => 'Enter Middle Name',
                'class' => 'form-register',
            ),
            'options' => array(
                'label' => 'Middle Name',
            ),
        ));
        $this->add(array(
            'name' => 'lastName',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
                'placeholder' => 'Enter Last Name',
                'required' => 'required',
                'class' => 'form-register',
            ),
            'options' => array(
                'label' => 'Last Name',
            ),
        ));

        $this->add(array(
            'name' => 'username',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
                'placeholder' => 'Enter User Name',
                'required' => 'required',
                'class' => 'form-register',
            ),
            'options' => array(
                'label' => 'User Name',
            ),
        ));

        $this->add(array(
            'name' => 'mobile',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
                'placeholder' => 'Enter Mobile Number ( 444-555-1234 / 246.555.8888 / 1235554567)',
                'required' => 'required',
                'class' => 'form-register',
            ),
            'options' => array(
                'label' => 'Mobile',
            ),
        ));
        $this->add(array(
            'name' => 'phone',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
                'placeholder' => 'Enter Phone Number ( 444-555-1234 / 246.555.8888 / 1235554567)',
                'class' => 'form-register',
            ),
            'options' => array(
                'label' => 'Phone',
            ),
        ));


        $this->add(array(
            'name' => 'email',
            'type' => 'Zend\Form\Element\Email',
            'attributes' => array(
                'placeholder' => 'Enter Email',
                'required' => 'required',
                'class' => 'form-register',
            ),
            'options' => array(
                'label' => 'Email',
            ),
        ));
        $this->add(array(
            'name' => 'confirmEmail',
            'type' => 'Zend\Form\Element\Email',
            'attributes' => array(
                'placeholder' => 'Confirm User Email',
                'class' => 'form-register',
            ),
            'options' => array(
                'label' => 'Confirm Email',
            ),
        ));
        $this->add(array(
            'name' => 'password',
            'type' => 'Zend\Form\Element\Password',
            'attributes' => array(
                'placeholder' => 'Enter Password',
                'class' => 'form-register',
            ),
            'options' => array(
                'label' => 'Password',
            ),
        ));
        $this->add(array(
            'name' => 'confirmPassword',
            'type' => 'Zend\Form\Element\Password',
            'attributes' => array(
                'placeholder' => 'Confirm User Password',
                'class' => 'form-register',
            ),
            'options' => array(
                'label' => 'Confirm Password',
            ),
        ));
        $this->add(array(
            'name' => 'securityQuestion',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
                'placeholder' => 'Enter Security Question',
                'required' => 'required',
                'class' => 'form-register',
            ),
            'options' => array(
                'label' => 'Security Question'
            ),
        ));
        $this->add(array(
            'name' => 'securityAnswer',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
                'placeholder' => 'Enter Security Answer',
                'required' => 'required',
                'class' => 'form-register',
            ),
            'options' => array(
                'label' => 'Security Answer',
            ),
        ));

        $this->add(array(
            'name' => 'identificationType',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
                'placeholder' => 'Enter Identification Type (National ID, or Passport, etc)',
                'required' => 'required',
                'class' => 'form-register',
            ),
            'options' => array(
                'label' => 'Identification Type',
            ),
        ));
        $this->add(array(
            'name' => 'identificationNumber',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
                'placeholder' => 'Enter Identification Number',
                'required' => 'required',
                'class' => 'form-register',
            ),
            'options' => array(
                'label' => 'Identification Number',
            ),
        ));
        $this->add(array(
            'name' => 'identificationExpiryDate',
            'type' => 'Zend\Form\Element\Date',
            'attributes' => array(
                'required' => 'required',
                'class' => 'form-register date',
                'type' => 'text',
            ),
            'options' => array(
                'label' => 'Identification Expiry Date',
                'format' => Time::DATE_FORMAT,
            ),
        ));
        $this->add(array(
            'name' => 'dateOfBirth',
            'type' => 'Zend\Form\Element\Date',
            'attributes' => array(
                'required' => 'required',
                'class' => 'form-register date',
                'type' => 'text',
            ),
            'options' => array(
                'label' => 'Date Of Birth',
                'format' => Time::DATE_FORMAT,
            ),
        ));
        $this->add(array(
            'name' => 'nationality',
            'type' => 'Zend\Form\Element\Select',
            'attributes' => array(
                'class' => 'form-register',
                'required' => 'required',
            ),
            'options' => array(
                'label' => 'Nationality',
                'value_options' => $countries,
                'empty_option' => self::EMPTY_SELECT_VALUE,
            ),
        ));
        $this->add(array(
            'name' => 'language',
            'type' => 'Zend\Form\Element\Select',
            'attributes' => array(
                'class' => 'form-register',
                'required' => 'required',
            ),
            'options' => array(
                'label' => 'Language',
                'value_options' => $languages,
                'empty_option' => self::EMPTY_SELECT_VALUE,
            ),
        ));

        $this->add(array(
            'name' => 'addressOne',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
                'placeholder' => 'Enter Address Line 1',
                'required' => 'required',
                'class' => 'form-register',
            ),
            'options' => array(
                'label' => 'Address Line 1',
            ),
        ));
        $this->add(array(
            'name' => 'addressTwo',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
                'placeholder' => 'Enter Address Line 2',
                'class' => 'form-register',
            ),
            'options' => array(
                'label' => 'Address Line 2',
            ),
        ));
        $this->add(array(
            'name' => 'city',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
                'placeholder' => 'Enter City',
                'required' => 'required',
                'class' => 'form-register',
            ),
            'options' => array(
                'label' => 'City',
            ),
        ));
        $this->add(array(
            'name' => 'zipCode',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
                'placeholder' => 'Enter Zip Code',
                'class' => 'form-register',
            ),
            'options' => array(
                'label' => 'Zip Code',
            ),
        ));
        $this->add(array(
            'name' => 'country',
            'type' => 'Zend\Form\Element\Select',
            'attributes' => array(
                'class' => 'form-register',
                'required' => 'required',
            ),
            'options' => array(
                'label' => 'Country',
                'value_options' => $countries,
                'empty_option' => self::EMPTY_SELECT_VALUE,
            ),
        ));

        $this->add(array(
            'name' => 'photo',
            'type' => 'Zend\Form\Element\File',
            'options' => array(
                'label' => 'Picture',
            ),
        ));

        $this->add(array(
            'name' => 'roles',
            'type' => 'DoctrineModule\Form\Element\ObjectMultiCheckbox',
            'attributes' => array(
                'class' => 'mar',
            ),
            'options' => array(
                'label' => '<label class="legendLabel"><div>Roles</div></label>',
                'object_manager' => $this->query->entityManager,
                'target_class' => 'Users\Entity\Role',
                'property' => 'name',
                'find_method' => array(
                    'name' => 'getRoles',
                    'params' => array(
                        'excludedRoles' => $excludedRoles
                    )
                ),
                'label_options' => array(
                    'disable_html_escape' => true,
                ),
                'label_attributes' => array(
                    'class' => "col-md-4",
                )
            ),
        ));

        $this->add(array(
            'name' => 'agreements',
            'type' => 'Users\Form\AgreementsFieldset',
        ));

        $this->add(array(
            'name' => 'privacyStatement',
            'type' => 'Zend\Form\Element\Checkbox',
            'attributes' => array(
                'class' => 'form-control',
                'required' => 'required',
            ),
            'options' => array(
                'label' => 'Privacy Statement',
                'checked_value' => Statement::STATEMENT_AGREE,
                'unchecked_value' => Statement::STATEMENT_DISAGREE
            ),
        ));

        if (!$this->isAdminUser) {
            $this->add(array(
                'type' => 'Zend\Form\Element\Captcha',
                'name' => 'captcha',
                'attributes' => array(
                    'class' => 'form-register classCaptcha',
                    'required' => 'required',
                ),
                'options' => array(
                    'label' => 'Please verify you are human.',
                    'label_attributes' => array(
                        'class' => 'classCaptcha'
                    ),
                    'captcha' => array(
                        'class' => 'Image',
                        'options' => array(
                            'font' => APPLICATION_PATH . '/fonts/Arctik.ttf',
                            'width' => 200,
                            'height' => 65,
                            'dotNoiseLevel' => 20,
                            'lineNoiseLevel' => 3,
                        ),
                    ),
                ),
            ));
        }
        $this->add(array(
            'name' => 'id',
            'type' => 'Zend\Form\Element\Hidden',
        ));

        // Add buttons fieldset
        $buttonsFieldset = new ButtonsFieldset(/* $name = */ null, /* $options = */ array("create_button_only" => true));
        $this->add($buttonsFieldset);
    }

}
