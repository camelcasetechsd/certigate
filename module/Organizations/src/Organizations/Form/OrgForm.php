<?php

namespace Organizations\Form;

use Zend\Form\FormInterface;
use Utilities\Form\Form;
use DoctrineModule\Persistence\ObjectManagerAwareInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * User Form
 * 
 * Handles User form setup
 * 
 * 
 * @property Utilities\Service\Query\Query $query
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
            'name' => 'longtitude',
            'continue_if_empty' => true,
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
                'placeholder' => 'Enter Longtitude',
                'class' => 'form-control',
                'allow_empty' => true,
                'continue_if_empty' => false
            ),
            'options' => array(
                'label' => 'Longtitude',
            ),
        ));
        $this->add(array(
            'name' => 'latitude',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
                'placeholder' => 'Enter Latitude',
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => 'Latitude',
            ),
            'validators' => array(
                'Empty' => true
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
                'format' => 'm/d/Y',
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
            'name' => 'wireTransferAttachment',
            'type' => 'Zend\Form\Element\File',
            'options' => array(
                'label' => 'Wire Transfer Attachment',
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
                'label' => 'ATC License Number',
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
                'format' => 'm/d/Y',
            ),
        ));

        $this->add(array(
            'name' => 'atcLicenseAttachment',
            'type' => 'Zend\Form\Element\File',
            'attributes' => array(
                'class' => 'atcSet',
            ),
            'options' => array(
                'label' => 'ATC License Attachment',
                'required' => 'required',
                'label_attributes' => array(
                    'class' => 'atcSet',
                ),
            ),
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
                'empty_option' => "--Select--",
                'value_options' => $options['staticOfficeVersions']
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
                'empty_option' => "--Select--",
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
                'empty_option' => "--Select--",
                'value_options' => $options['staticOss'],
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
                'empty_option' => "--Select--",
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
                'format' => 'm/d/Y',
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
                'class' => 'form-control atpSet',
                'multiple' => false,
            ),
            'options' => array(
                'label' => 'Training Manager',
                'label_attributes' => array(
                    'class' => ' atpSet',
                ),
                'object_manager' => $this->query->entityManager,
                'target_class' => 'Users\Entity\User',
                'property' => 'firstName',
                'empty_item_label' => '--Select--',
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
                'class' => 'form-control atcSet',
                'multiple' => false,
            ),
            'options' => array(
                'label' => 'Test Center Admin',
                'label_attributes' => array(
                    'class' => ' atcSet',
                ),
                'object_manager' => $this->query->entityManager,
                'target_class' => 'Users\Entity\User',
                'property' => 'firstName',
                'display_empty_item' => true,
                'empty_item_label' => '--Select--',
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
                'empty_item_label' => '--Select--',
                'label_generator' => function($targetEntity) {
                    return $targetEntity->getFirstName() . ' ' . $targetEntity->getMiddleName() . ' ' . $targetEntity->getLastName();
                },
            )
        ));


        $this->add(array(
            'name' => 'id',
            'type' => 'Zend\Form\Element\Hidden',
        ));
        $this->add(array(
            'name' => 'active',
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

    public function bind($object, $flags = FormInterface::VALUES_NORMALIZED)
    {
        parent::bind($object, $flags);
//
//        $users = $object->getOrganizationUsers();
//
//        
//        
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
