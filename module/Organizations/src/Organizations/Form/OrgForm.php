<?php

namespace Organizations\Form;

use Zend\Form\FormInterface;
use Utilities\Form\Form;
use DoctrineModule\Persistence\ObjectManagerAwareInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Utilities\Service\Time;
use Utilities\Service\Status;
use Translation\Service\Locale\Locale as ApplicationLocale;

/**
 * User Form
 * 
 * Handles User form setup
 * 
 * 
 * @property Utilities\Service\Query\Query $query
 * @property Translation\Service\Translator\TranslatorHandler $translatorHandler
 * @property Translation\Service\Locale\ApplicationLocale $applicationLocale
 * 
 * @package organizations
 * @subpackage form
 */
class OrgForm extends Form implements ObjectManagerAwareInterface
{

    protected $objectManager;

    /**
     *
     * @var Utilities\Service\Query\Query 
     */
    protected $query;

    /**
     *
     * @var Translation\Service\Locale\Locale
     */
    protected $applicationLocale;

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
        $this->needAdminApproval = true;
        $this->query = $options['query'];
        $this->applicationLocale = $options['applicationLocale'];
        unset($options['query']);
        unset($options['applicationLocale']);
        parent::__construct($name, $options);
        $this->setAttribute('class', 'form form-horizontal gllpLatlonPicker');

        $this->add(array(
            'name' => 'type',
            'type' => 'Zend\Form\Element\Hidden',
        ));

        $this->add(array(
            'name' => 'commercialName',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
                'placeholder' => 'Enter Commercial Name',
                'required' => 'required',
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => 'Commercial Name',
            ),
                )
        );

        $this->add(array(
            'name' => 'commercialNameAr',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
                'required' => 'required',
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => 'Commercial Name in Arabic',
            ),
                )
        );

        $this->add(array(
            'name' => 'ownerName',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
                'placeholder' => 'Enter Owner Name',
                'required' => 'required',
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => 'Owner Name',
            ),
        ));

        $this->add(array(
            'name' => 'ownerNameAr',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
                'required' => 'required',
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => 'Owner Name in Arabic',
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
                'label' => '<div>Location</div><div class="gllpMap">Google Maps</div>',
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
            'continue_if_empty' => true,
            'type' => 'Zend\Form\Element\Hidden',
            'attributes' => array(
                'placeholder' => 'Enter Longitude',
                'class' => 'form-control gllpLongitude',
                'allow_empty' => true,
                'continue_if_empty' => false
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
            ),
            'validators' => array(
                'Empty' => true
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
            'name' => 'region',
            'type' => 'DoctrineModule\Form\Element\ObjectSelect',
            'attributes' => array(
                'required' => 'required',
                'class' => 'form-control',
                'multiple' => true,
            ),
            'options' => array(
                'label' => 'Region',
                'object_manager' => $this->query->entityManager,
                'target_class' => 'Organizations\Entity\OrganizationRegion',
                'property' => 'title',
                'display_empty_item' => true,
                'label_generator' => function($targetEntity) {
                    return $targetEntity->getTitle();
                },
            )
        ));
        $this->add(array(
            'name' => 'governorate',
            'type' => 'DoctrineModule\Form\Element\ObjectSelect',
            'attributes' => array(
                'required' => 'required',
                'class' => 'form-control',
                'multiple' => true,
            ),
            'options' => array(
                'label' => 'Governorate',
                'object_manager' => $this->query->entityManager,
                'target_class' => 'Organizations\Entity\OrganizationGovernorate',
                'property' => 'title',
                'display_empty_item' => true,
                'label_generator' => function($targetEntity) {
                    return $targetEntity->getTitle();
                },
            )
        ));

        $this->add(array(
            'name' => 'ownerNationalId',
            'type' => 'Zend\Form\Element\Number',
            'attributes' => array(
                'placeholder' => 'Enter Owner National Id',
                'required' => 'required',
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => 'Owner National Id',
            ),
        ));

        $this->add(array(
            'name' => 'CRNo',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
                'placeholder' => 'Enter CR',
                'required' => 'required',
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => 'CR',
            ),
        ));

        $this->add(array(
            'name' => 'CRExpiration',
            'type' => 'Zend\Form\Element\Date',
            'attributes' => array(
                'placeholder' => 'Enter CR Expiration Date',
                'required' => 'required',
                'class' => 'form-control date',
                'type' => 'text',
            ),
            'options' => array(
                'label' => 'CR Expiration Date',
                'format' => Time::DATE_FORMAT,
            ),
        ));

        $this->add(array(
            'name' => 'CRAttachment',
            'type' => 'Zend\Form\Element\File',
            'options' => array(
                'label' => 'CR Attachment',
                'required' => 'required',
            ),
            'attributes' => array(
                'required' => true,
            )
        ));

        $this->add(array(
            'name' => 'phone1',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
                'placeholder' => 'Enter Phone Number',
                'required' => 'required',
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => 'Phone 1',
            ),
        ));

        $this->add(array(
            'name' => 'phone2',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
                'placeholder' => 'Enter Phone Number',
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => 'Phone 2',
            ),
        ));

        $this->add(array(
            'name' => 'phone3',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
                'placeholder' => 'Enter Phone Number',
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => 'Phone 3',
            ),
        ));

        $this->add(array(
            'name' => 'fax',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
                'placeholder' => 'Enter fax number',
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => 'Fax',
            ),
        ));

        $this->add(array(
            'name' => 'addressLine1',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
                'placeholder' => 'Enter Address',
                'required' => 'required',
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => 'Address 1',
            ),
        ));

        $this->add(array(
            'name' => 'addressLine1Ar',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
                'class' => 'form-control',
                'required' => 'required',
            ),
            'options' => array(
                'label' => 'Address 1 in Arabic',
            ),
        ));

        $this->add(array(
            'name' => 'addressLine2',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
                'placeholder' => 'Enter Address',
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => 'Address 2',
            ),
        ));

        $this->add(array(
            'name' => 'addressLine2Ar',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => 'Address 2 in Arabic',
            ),
        ));

        $this->add(array(
            'name' => 'city',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
                'placeholder' => 'Enter City',
                'required' => 'required',
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => 'City',
            ),
        ));

        $this->add(array(
            'name' => 'cityAr',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
                'required' => 'required',
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => 'City in Arabic',
            ),
        ));

        $this->add(array(
            'name' => 'zipCode',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
                'placeholder' => 'Enter Zip Code',
                'required' => 'required',
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => 'Zip Code',
            ),
        ));

        $this->add(array(
            'name' => 'website',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
                'placeholder' => 'Enter Website',
                'required' => 'required',
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => 'Website',
            ),
        ));

        $this->add(array(
            'name' => 'email',
            'type' => 'Zend\Form\Element\Email',
            'attributes' => array(
                'placeholder' => 'Enter Email',
                'required' => 'required',
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => 'Email',
            ),
        ));







//////////////////////////////////////////////////////////////////////
        /////////// ATP Data ///////////////
        $this->add(array(
            'name' => 'atcLicenseNo',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
                'placeholder' => 'Enter License',
                'required' => 'required',
                'class' => 'form-control atcSet',
            ),
            'options' => array(
                'label' => 'ATC License',
                'label_attributes' => array(
                    'class' => 'atcSet',
                ),
            ),
        ));
        $this->add(array(
            'name' => 'atcLicenseExpiration',
            'type' => 'Zend\Form\Element\Date',
            'attributes' => array(
                'placeholder' => 'Enter License Expiration Date',
                'required' => 'required',
                'class' => 'form-control date atcSet',
                'type' => 'text',
            ),
            'options' => array(
                'label' => 'ATC License Expiration Date',
                'label_attributes' => array(
                    'class' => 'atcSet',
                ),
                'format' => Time::DATE_FORMAT,
            ),
        ));

        $this->add(array(
            'name' => 'atcLicenseAttachment',
            'type' => 'Zend\Form\Element\File',
            'attributes' => array(
                'class' => 'atcSet',
                'required' => 'required',
            ),
            'options' => array(
                'label' => 'ATC License Attachment',
                'label_attributes' => array(
                    'class' => 'atcSet',
                ),
            ),
        ));

        $this->add(array(
            'name' => 'atcWireTransferAttachment',
            'type' => 'Zend\Form\Element\File',
            'options' => array(
                'label' => 'ATC Wire Transfer Attachment',
                'required' => 'required',
            ),
            'attributes' => array(
                'required' => true,
            )
        ));


        $this->add(array(
            'name' => 'labsNo',
            'type' => 'Zend\Form\Element\Number',
            'attributes' => array(
                'placeholder' => 'Enter Labs Number',
                'required' => 'required',
                'class' => 'form-control atcSet',
            ),
            'options' => array(
                'label' => 'Labs Number',
                'label_attributes' => array(
                    'class' => 'atcSet',
                ),
            ),
        ));

        $this->add(array(
            'name' => 'pcsNo_lab',
            'type' => 'Zend\Form\Element\Number',
            'attributes' => array(
                'placeholder' => 'Enter PCs Number Per Lab',
                'required' => 'required',
                'class' => 'form-control atcSet',
            ),
            'options' => array(
                'label' => 'PCs Number / Lab',
                'label_attributes' => array(
                    'class' => 'atcSet',
                ),
            ),
        ));

        $this->add(array(
            'name' => 'internetSpeed_lab',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
                'placeholder' => 'Enter Internet Speed Per Lab',
                'required' => 'required',
                'class' => 'form-control atcSet',
            ),
            'options' => array(
                'label' => 'Internet Speed / Lab',
                'label_attributes' => array(
                    'class' => 'atcSet',
                ),
            ),
        ));
        $this->add(array(
            'name' => 'operatingSystem',
            'type' => 'Zend\Form\Element\Select',
            'attributes' => array(
                'placeholder' => 'Enter Operating System',
                'required' => 'required',
                'class' => 'form-control atcSet',
            ),
            'options' => array(
                'label' => 'Operating System',
                'label_attributes' => array(
                    'class' => 'atcSet',
                ),
                'empty_option' => self::EMPTY_SELECT_VALUE,
                'value_options' => $options['staticOss']
            ),
        ));

        $this->add(array(
            'name' => 'operatingSystemLang',
            'type' => 'Zend\Form\Element\Select',
            'attributes' => array(
                'placeholder' => 'Enter Operating System Language',
                'required' => 'required',
                'class' => 'form-control atcSet',
            ),
            'options' => array(
                'label' => 'Operating System Language',
                'label_attributes' => array(
                    'class' => 'atcSet',
                ),
                'empty_option' => self::EMPTY_SELECT_VALUE,
                'value_options' => $options['staticLangs']
            ),
        ));

        $this->add(array(
            'name' => 'officeVersion',
            'type' => 'Zend\Form\Element\Select',
            'attributes' => array(
                'placeholder' => 'Enter Microseft Office Version',
                'required' => 'required',
                'class' => 'form-control atcSet',
            ),
            'options' => array(
                'label' => 'Microseft Office Version',
                'empty_option' => self::EMPTY_SELECT_VALUE,
                'value_options' => $options['staticOfficeVersions'],
                'label_attributes' => array(
                    'class' => 'atpLicenseNo atcSet',
                ),
            ),
        ));


        $this->add(array(
            'name' => 'officeLang',
            'type' => 'Zend\Form\Element\Select',
            'attributes' => array(
                'required' => 'required',
                'class' => 'form-control atcSet',
            ),
            'options' => array(
                'label' => 'Microsoft office Language',
                'label_attributes' => array(
                    'class' => 'atcSet',
                ),
                'empty_option' => self::EMPTY_SELECT_VALUE,
                'value_options' => $options['staticLangs']
            ),
        ));


//            END OF ATP
///////////////////////////////////////////////////////////////
        //////////// ATC DATA /////////////
        $this->add(array(
            'name' => 'atpLicenseNo',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
                'placeholder' => 'Enter License',
                'required' => 'required',
                'class' => 'form-control atpSet',
            ),
            'options' => array(
                'label' => 'ATP License',
                'label_attributes' => array(
                    'class' => 'atpSet',
                ),
            ),
        ));

        $this->add(array(
            'name' => 'atpLicenseExpiration',
            'type' => 'Zend\Form\Element\Date',
            'attributes' => array(
                'placeholder' => 'Enter License Expiration Date',
                'required' => 'required',
                'class' => 'form-control date atpSet ',
                'type' => 'text',
            ),
            'options' => array(
                'label' => 'ATP License Expiration Date',
                'label_attributes' => array(
                    'class' => 'atpSet',
                ),
                'format' => Time::DATE_FORMAT,
            )
        ));

        $this->add(array(
            'name' => 'atpLicenseAttachment',
            'type' => 'Zend\Form\Element\File',
            'attributes' => array(
                'class' => 'atpSet',
                'required' => 'required',
            ),
            'options' => array(
                'label' => 'ATP License Attachment',
                'label_attributes' => array(
                    'class' => 'atpSet',
                ),
            ),
        ));
        
         $this->add(array(
            'name' => 'atpWireTransferAttachment',
            'type' => 'Zend\Form\Element\File',
            'options' => array(
                'label' => 'ATP Wire Transfer Attachment',
                'required' => 'required',
            ),
            'attributes' => array(
                'required' => true,
            )
        ));


        $this->add(array(
            'name' => 'classesNo',
            'type' => 'Zend\Form\Element\Number',
            'attributes' => array(
                'placeholder' => 'Enter Classes Number',
                'required' => 'required',
                'class' => 'form-control atpSet',
            ),
            'options' => array(
                'label' => 'Classes Number',
                'label_attributes' => array(
                    'class' => 'atpSet',
                ),
            ),
        ));

        $this->add(array(
            'name' => 'pcsNo_class',
            'type' => 'Zend\Form\Element\Number',
            'attributes' => array(
                'placeholder' => 'Enter PCs Number Per Class',
                'required' => 'required',
                'class' => 'form-control atpSet',
            ),
            'options' => array(
                'label' => 'PCs Number / Class',
                'label_attributes' => array(
                    'class' => ' atpSet',
                ),
            ),
        ));

//        END OF ATC
//////////////////////////////////////////////////////////////////////

        $this->add(array(
            'name' => 'trainingManager_id',
            'type' => 'DoctrineModule\Form\Element\ObjectSelect',
            'attributes' => array(
                'class' => 'form-control atpSet notReqOnEdit',
                'multiple' => false,
            ),
            'options' => array(
                'label' => 'Training Manager',
                'label_attributes' => array(
                    'class' => ' atpSet notReqOnEdit',
                ),
                'object_manager' => $this->query->entityManager,
                'target_class' => 'Users\Entity\User',
                'property' => 'firstName',
                'empty_item_label' => self::EMPTY_SELECT_VALUE,
                'display_empty_item' => true,
                'label_generator' => function($targetEntity) {
            return $targetEntity->getFirstName() . ' ' . $targetEntity->getMiddleName() . ' ' . $targetEntity->getLastName();
        },
            )
        ));

        $this->add(array(
            'name' => 'testCenterAdmin_id',
            'type' => 'DoctrineModule\Form\Element\ObjectSelect',
            'attributes' => array(
                'class' => 'form-control atcSet notReqOnEdit',
                'multiple' => false,
                'required' => false
            ),
            'options' => array(
                'label' => 'Test Center Admin',
                'label_attributes' => array(
                    'class' => ' atcSet notReqOnEdit',
                ),
                'object_manager' => $this->query->entityManager,
                'target_class' => 'Users\Entity\User',
                'property' => 'firstName',
                'display_empty_item' => true,
                'empty_item_label' => self::EMPTY_SELECT_VALUE,
                'label_generator' => function($targetEntity) {
            return $targetEntity->getFirstName() . ' ' . $targetEntity->getMiddleName() . ' ' . $targetEntity->getLastName();
        },
            )
        ));

        $this->add(array(
            'name' => 'focalContactPerson_id',
            'type' => 'DoctrineModule\Form\Element\ObjectSelect',
            'attributes' => array(
                'required' => 'required',
                'class' => 'form-control',
                'multiple' => false,
            ),
            'options' => array(
                'label' => 'Focal Contact Person',
                'object_manager' => $this->query->entityManager,
                'target_class' => 'Users\Entity\User',
                'property' => 'firstName',
                'display_empty_item' => true,
                'empty_item_label' => self::EMPTY_SELECT_VALUE,
                'label_generator' => function($targetEntity) {
                    if ($this->applicationLocale->getCurrentLocale() == ApplicationLocale::LOCALE_EN_US) {

                        return $targetEntity->getFirstName() . ' ' . $targetEntity->getMiddleName() . ' ' . $targetEntity->getLastName();
                    }
                    else {
                        return $targetEntity->getFirstNameAr() . ' ' . $targetEntity->getMiddleNameAr() . ' ' . $targetEntity->getLastNameAr();
                    }
                },
            )
        ));


        $this->add(array(
            'name' => 'id',
            'type' => 'Zend\Form\Element\Hidden',
        ));

        $this->add(array(
            'name' => 'atpPrivacyStatement',
            'type' => 'Zend\Form\Element\Checkbox',
            'attributes' => array(
                'class' => 'form-control atpSet checkboxAgreement',
                'required' => true,
            ),
            'options' => array(
                'label' => 'ATP Privacy Statement',
                'label_attributes' => array(
                    'class' => ' atpSet',
                ),
            ),
        ));

        $this->add(array(
            'name' => 'atcPrivacyStatement',
            'type' => 'Zend\Form\Element\Checkbox',
            'attributes' => array(
                'class' => 'form-control atcSet checkboxAgreement',
                'required' => true,
            ),
            'options' => array(
                'label' => 'ATC Privacy Statement',
                'label_attributes' => array(
                    'class' => ' atcSet',
                ),
            ),
        ));

        if ($this->isAdminUser === true) {
            $this->add(array(
                'name' => 'status',
                'type' => 'Zend\Form\Element\Checkbox',
                'attributes' => array(
                    'class' => 'form-control',
                ),
                'options' => array(
                    'label' => 'Status',
                    'checked_value' => Status::STATUS_ACTIVE,
                    'unchecked_value' => Status::STATUS_INACTIVE
                ),
            ));
        }

        $this->add(array(
            'name' => 'submit',
            'type' => 'Zend\Form\Element\Submit',
            'attributes' => array(
                'class' => 'btn btn-success inline',
                'value' => 'Create',
            )
        ));

        $this->add(array(
            'name' => 'saveState',
            'type' => 'Zend\Form\Element',
            'attributes' => array(
                'class' => 'btn btn-info saveStateButton',
                'value' => 'Save State',
                'type' => 'button',
            )
        ));

        $this->add(array(
            'name' => 'reset',
            'type' => 'Zend\Form\Element',
            'attributes' => array(
                'class' => 'btn btn-danger resetButton orgResetButton',
                'value' => 'Reset',
                'type' => 'button',
            )
        ));
    }

    public function bind($object, $flags = FormInterface::VALUES_NORMALIZED)
    {
        parent::bind($object, $flags);

        $regions = $object->getRegions();
        $regionArray = array();
        foreach ($regions as $region) {
            array_push($regionArray, $region->getId());
        }
        $this->get('region')->setValue($regionArray);


        $governorates = $object->getGovernorates();
        $governorateArray = array();
        foreach ($governorates as $gov) {
            array_push($governorateArray, $gov->getId());
        }
        $this->get('governorate')->setValue($governorateArray);


        $focalContactPerson = $object->getFocalContactPerson();
        if (isset($focalContactPerson->id) && $focalContactPerson != null) {
            $this->get('focalContactPerson_id')->setValue($focalContactPerson->id);
        }
    }

    public function setObjectManager(ObjectManager $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    public function getObjectManager()
    {
        return $this->objectManager;
    }

}
