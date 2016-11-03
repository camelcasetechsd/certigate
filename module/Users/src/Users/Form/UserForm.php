<?php

namespace Users\Form;

use Zend\Form\FormInterface;
use Utilities\Form\Form;
use Users\Service\Statement;
use Utilities\Service\Time;
use Utilities\Form\ButtonsFieldset;
use Translation\Service\Locale\Locale;
use Users\Service\CountryCodes;

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
        $countriesService = $options['countriesService'];
        $languagesService = $options['languagesService'];
        $applicationLocale = $options['applicationLocale'];
        $currentLocale = $applicationLocale->getCurrentLocale();
        $currentLanguageCode = $applicationLocale->getCurrentLanguageCode();
        $excludedRoles = $options['excludedRoles'];
        unset($options['query']);
        unset($options['countriesService']);
        unset($options['languagesService']);
        unset($options['currentLocale']);
        unset($options['excludedRoles']);
        parent::__construct($name, $options);

        $this->setAttribute('class', 'form form-horizontal gllpLatlonPicker');

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
            'name' => 'firstNameAr',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
                'placeholder' => 'Enter First Name in Arabic',
                'class' => 'form-register',
            ),
            'options' => array(
                'label' => 'First Name in Arabic',
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
            'name' => 'middleNameAr',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
                'placeholder' => 'Enter Middle Name in Arabic',
                'class' => 'form-register',
            ),
            'options' => array(
                'label' => 'Middle Name in Arabic',
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
            'name' => 'lastNameAr',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
                'placeholder' => 'Enter Last Name in Arabic',
                'class' => 'form-register',
            ),
            'options' => array(
                'label' => 'Last Name in Arabic',
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

        // mobile fields
        $this->add(array(
            'name' => 'mobileCountryCode',
            'type' => 'Zend\Form\Element\Select',
            'attributes' => array(
                'class' => 'form-register',
                'required' => 'required',
                // making KSA default country code
                'value' => '+966'
            ),
            'options' => array(
                'empty_option' => self::EMPTY_SELECT_VALUE,
                'value_options' => CountryCodes::fetchCodes(),
            ),
        ));
        $this->add(array(
            'name' => 'mobileAreaCode',
            'type' => 'Zend\Form\Element\Number',
            'attributes' => array(
                'placeholder' => 'Enter mobile Area code 2-3 digits',
                'required' => 'required',
                'class' => 'form-register',
            ),
            'options' => array(
                'label' => 'Mobile',
            ),
        ));
        $this->add(array(
            'name' => 'mobile',
            'type' => 'Zend\Form\Element\Number',
            'attributes' => array(
                'placeholder' => 'Enter mobile Number 6-8 digits',
                'required' => 'required',
                'class' => 'form-register',
            ),
            'options' => array(
                'label' => 'Mobile',
                'description' => 'e.g. : ( +XXX-XXX-XXXXXXXX ), mobile will be ignored if no country code is selected '
            ),
        ));
        // End Mobile
        // phone fields
        $this->add(array(
            'name' => 'phoneCountryCode',
            'type' => 'Zend\Form\Element\Select',
            'options' => array(
                'empty_option' => self::EMPTY_SELECT_VALUE,
                'value_options' => CountryCodes::fetchCodes(),
            ),
            'attributes' => array(
                'class' => 'form-register',
                'required' => 'required',
                // making KSA default country code
                'value' => '+966'
            ),
        ));
        $this->add(array(
            'name' => 'phoneAreaCode',
            'type' => 'Zend\Form\Element\Number',
            'attributes' => array(
                'placeholder' => 'Enter 2-3 digits Area Code',
                'class' => 'form-register',
                'required' => 'required',
            ),
            'options' => array(
            ),
        ));
        $this->add(array(
            'name' => 'phone',
            'type' => 'Zend\Form\Element\Number',
            'attributes' => array(
                'placeholder' => 'Enter Phone Number 6-8 digits',
                'class' => 'form-register',
                'required' => 'required',
            ),
            'options' => array(
                'label' => 'Phone',
                'description' => 'e.g. : ( +XXX-XXX-XXXXXXXX ), phone will be ignored if no country code is selected '
            ),
        ));
        ///////// End phone

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
                'class' => 'form-register',
            ),
            'options' => array(
                'label' => 'Identification Number',
            ),
        ));

        if ($currentLocale == Locale::LOCALE_AR_AR) {

            $this->add(array(
                'name' => 'identificationExpiryDateHj',
                'type' => 'Zend\Form\Element\Date',
                'attributes' => array(
                    'class' => 'form-register new-hijriDate-ar',
                    'type' => 'text',
                ),
                'options' => array(
                    'label' => 'Hijri Identification Expiry Date',
                    'format' => Time::DATE_FORMAT,
                ),
            ));

            $this->add(array(
                'name' => 'identificationExpiryDate',
                'type' => 'Zend\Form\Element\Date',
                'attributes' => array(
                    'class' => 'form-register new-gregorianDate-ar',
                    'type' => 'text',
                ),
                'options' => array(
                    'label' => 'Identification Expiry Date',
                    'format' => Time::DATE_FORMAT,
                ),
            ));



            $this->add(array(
                'name' => 'dateOfBirthHj',
                'type' => 'Zend\Form\Element\Date',
                'attributes' => array(
                    'class' => 'form-register new-hijriDate-ar',
                    'type' => 'text',
                ),
                'options' => array(
                    'label' => 'Hijri Date Of Birth',
                    'format' => Time::DATE_FORMAT,
                ),
            ));
            $this->add(array(
                'name' => 'dateOfBirth',
                'type' => 'Zend\Form\Element\Date',
                'attributes' => array(
                    'class' => 'form-register new-gregorianDate-ar',
                    'type' => 'text',
                ),
                'options' => array(
                    'label' => 'Date Of Birth',
                    'format' => Time::DATE_FORMAT,
                ),
            ));
        }
        else {

            $this->add(array(
                'name' => 'identificationExpiryDateHj',
                'type' => 'Zend\Form\Element\Date',
                'attributes' => array(
                    'class' => 'form-register new-hijriDate',
                    'type' => 'text',
                ),
                'options' => array(
                    'label' => 'Hijri Identification Expiry Date',
                    'format' => Time::DATE_FORMAT,
                ),
            ));
            $this->add(array(
                'name' => 'identificationExpiryDate',
                'type' => 'Zend\Form\Element\Date',
                'attributes' => array(
                    'class' => 'form-register new-gregorianDate',
                    'type' => 'text',
                ),
                'options' => array(
                    'label' => 'Identification Expiry Date',
                    'format' => Time::DATE_FORMAT,
                ),
            ));


            $this->add(array(
                'name' => 'dateOfBirthHj',
                'type' => 'Zend\Form\Element\Date',
                'attributes' => array(
                    'class' => 'form-register new-hijriDate',
                    'type' => 'text',
                ),
                'options' => array(
                    'label' => 'Hijri Date Of Birth',
                    'format' => Time::DATE_FORMAT,
                ),
            ));
            $this->add(array(
                'name' => 'dateOfBirth',
                'type' => 'Zend\Form\Element\Date',
                'attributes' => array(
                    'class' => 'form-register new-gregorianDate',
                    'type' => 'text',
                ),
                'options' => array(
                    'label' => 'Date Of Birth',
                    'format' => Time::DATE_FORMAT,
                ),
            ));
        }

        $this->add(array(
            'name' => 'nationality',
            'type' => 'Zend\Form\Element\Select',
            'attributes' => array(
                'class' => 'form-register',
                'required' => 'required',
            ),
            'options' => array(
                'label' => 'Nationality',
                'value_options' => $countriesService->getAllCountries($currentLanguageCode),
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
            'name' => 'addressOneAr',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
                'placeholder' => 'Enter Address Line 1 in Arabic',
                'required' => 'required',
                'class' => 'form-register',
            ),
            'options' => array(
                'label' => 'Address Line 1 in Arabic',
            ),
        ));
        $this->add(array(
            'name' => 'addressTwoAr',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
                'placeholder' => 'Enter Address Line 2 in Arabic',
                'class' => 'form-register',
            ),
            'options' => array(
                'label' => 'Address Line 2 in Arabic',
            ),
        ));
        $this->add(array(
            'name' => 'mapSearch',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
                'placeholder' => 'Search In Map',
                'class' => 'form-control gllpSearchField',
            ),
            'options' => array(
                'label' => 'Location',
                'label_options' => array(
                    'disable_html_escape' => true,
                )
            ),
        ));

        $this->add(array(
            'name' => 'mapSearchButton',
            'type' => 'Zend\Form\Element',
            'attributes' => array(
                'class' => 'gllpSearchButton btn btn-primary',
                'value' => 'Search',
                'type' => 'button',
            )
        ));

        $this->add(array(
            'name' => 'longitude',
            'type' => 'Zend\Form\Element\Hidden',
            'attributes' => array(
                'placeholder' => 'Enter Longitude',
                'class' => 'form-control gllpLongitude',
            ),
            'options' => array(
                'label' => 'Longitude',
            ),
        ));

        $this->add(array(
            'name' => 'latitude',
            'type' => 'Zend\Form\Element\Hidden',
            'attributes' => array(
                'placeholder' => 'Enter Latitude',
                'class' => 'form-control gllpLatitude',
            ),
            'options' => array(
                'label' => 'Latitude',
            )
        ));

        $this->add(array(
            'name' => 'mapZoom',
            'type' => 'Zend\Form\Element\Hidden',
            'attributes' => array(
                'class' => 'form-control gllpZoom',
                'value' => 3,
            ),
            'options' => array(
                'label' => '',
            ),
            'validators' => array(
                'Empty' => true
            )
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
                'value_options' => $countriesService->getAllCountries($currentLanguageCode),
                'empty_option' => self::EMPTY_SELECT_VALUE,
            ),
        ));
        // setting saudi Arabia as default country
        $this->get('country')->setValue('SA');

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
                'class' => 'col-md-1',
            ),
            'options' => array(
                'label' => '<label class="legendLabel"><div>Roles</div></label>',
                'object_manager' => $this->query->entityManager,
                'target_class' => 'Users\Entity\Role',
                'property' => 'name' . (($currentLocale == Locale::LOCALE_AR_AR) ? "Ar" : ""),
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
                    'class' => "col-md-9 c-input role-label",
                )
            ),
        ));

        $class = 'form-control' . (($currentLocale == Locale::LOCALE_AR_AR) ? " pull-left" : "");
        $this->add(array(
            'name' => 'instructorStatement',
            'type' => 'Zend\Form\Element\Checkbox',
            'attributes' => array(
                'class' => $class,
            ),
            'options' => array(
                'label' => 'Instructor',
                'checked_value' => Statement::STATEMENT_AGREE,
                'unchecked_value' => Statement::STATEMENT_DISAGREE
            ),
        ));

        $this->add(array(
            'name' => 'proctorStatement',
            'type' => 'Zend\Form\Element\Checkbox',
            'attributes' => array(
                'class' => $class,
            ),
            'options' => array(
                'label' => 'Proctor',
                'checked_value' => Statement::STATEMENT_AGREE,
                'unchecked_value' => Statement::STATEMENT_DISAGREE
            ),
        ));

        $this->add(array(
            'name' => 'studentStatement',
            'type' => 'Zend\Form\Element\Checkbox',
            'attributes' => array(
                'class' => $class,
            ),
            'options' => array(
                'label' => 'Student',
                'checked_value' => Statement::STATEMENT_AGREE,
                'unchecked_value' => Statement::STATEMENT_DISAGREE
            ),
        ));

        $this->add(array(
            'name' => 'testCenterAdministratorStatement',
            'type' => 'Zend\Form\Element\Checkbox',
            'attributes' => array(
                'class' => $class,
            ),
            'options' => array(
                'label' => 'Test Center Administrator',
                'checked_value' => Statement::STATEMENT_AGREE,
                'unchecked_value' => Statement::STATEMENT_DISAGREE
            ),
        ));

        $this->add(array(
            'name' => 'trainingManagerStatement',
            'type' => 'Zend\Form\Element\Checkbox',
            'attributes' => array(
                'class' => $class,
            ),
            'options' => array(
                'label' => 'Training Manager',
                'checked_value' => Statement::STATEMENT_AGREE,
                'unchecked_value' => Statement::STATEMENT_DISAGREE
            ),
        ));

        $this->add(array(
            'name' => 'distributorStatement',
            'type' => 'Zend\Form\Element\Checkbox',
            'attributes' => array(
                'class' => $class,
            ),
            'options' => array(
                'label' => 'Distributor',
                'checked_value' => Statement::STATEMENT_AGREE,
                'unchecked_value' => Statement::STATEMENT_DISAGREE
            ),
        ));

        $this->add(array(
            'name' => 'resellerStatement',
            'type' => 'Zend\Form\Element\Checkbox',
            'attributes' => array(
                'class' => $class,
            ),
            'options' => array(
                'label' => 'Re-Seller',
                'checked_value' => Statement::STATEMENT_AGREE,
                'unchecked_value' => Statement::STATEMENT_DISAGREE
            ),
        ));

        $this->add(array(
            'name' => 'privacyStatement',
            'type' => 'Zend\Form\Element\Checkbox',
            'attributes' => array(
                'class' => $class,
                'required' => 'required',
            ),
            'options' => array(
                'label' => 'Privacy Statement',
                'checked_value' => Statement::STATEMENT_AGREE,
                'unchecked_value' => Statement::STATEMENT_DISAGREE
            ),
        ));
        
        if (!$this->isAdminUser && APPLICATION_ENV != "test") {
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
                            'dotNoiseLevel' => 10,
                            'lineNoiseLevel' => 0,
                            'wordlen' => 4
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
        $buttonsFieldset = new ButtonsFieldset(/* $name = */ null, /* $options = */ array("save_button_only" => true));
        $this->add($buttonsFieldset);
    }

    public function bind($object, $flags = FormInterface::VALUES_NORMALIZED)
    {
        parent::bind($object, $flags);
        // to prevent binding password value in edit form
        $this->get('password')->setValue('');
        if (!is_null($object->phone)) {
            $phone = explode('-', $object->phone);
            $this->get('phoneCountryCode')->setValue($phone[0]);
            $this->get('phoneAreaCode')->setValue($phone[1]);
            $this->get('phone')->setValue($phone[2]);
        }
        $mobile = explode('-', $object->mobile);
        $this->get('mobileCountryCode')->setValue($mobile[0]);
        $this->get('mobileAreaCode')->setValue($mobile[1]);
        $this->get('mobile')->setValue($mobile[2]);
    }

}
