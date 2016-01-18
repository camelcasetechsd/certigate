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
 * @package users
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
//        $excludedRoles = $options['excludedRoles'];
        unset($options['query']);
//        unset($options['countries']);
//        unset($options['languages']);
//        unset($options['excludedRoles']);
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
                'placeholder' => 'Commercial Name',
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
                'placeholder' => 'Owner Name',
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
                'placeholder' => 'Longtitude',
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
                'placeholder' => 'Latitude',
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
                'placeholder' => 'Owner National Id',
                'required' => 'required',
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => 'Owner National Id',
            ),
        ));

        $this->add(array(
            'name' => 'CRNo',
            'type' => 'Zend\Form\Element\Number',
            'attributes' => array(
                'placeholder' => 'Enter CR Number',
                'required' => 'required',
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => 'CR Number',
            ),
        ));

        $this->add(array(
            'name' => 'CRExpiration',
            'type' => 'Zend\Form\Element\Date',
            'attributes' => array(
                'placeholder' => 'Enter Phone Number',
                'required' => 'required',
                'class' => 'form-control datepicker',
            ),
            'options' => array(
                'label' => 'CR Expiration Date',
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
                'placeholder' => 'Enter your city',
                'required' => 'required',
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => 'City',
            ),
        ));

        $this->add(array(
            'name' => 'zipCode',
            'type' => 'Zend\Form\Element\Number',
            'attributes' => array(
                'placeholder' => 'Enter ZipCode',
                'required' => 'required',
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => 'ZipCode',
            ),
        ));

        $this->add(array(
            'name' => 'website',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
                'placeholder' => 'Enter your website',
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
            'name' => 'atpLicenseNo',
            'type' => 'Zend\Form\Element\Number',
            'attributes' => array(
                'placeholder' => 'Enter Phone Number',
                'required' => 'required',
                'class' => 'form-control atpSet',
            ),
            'options' => array(
                'label' => 'ATP Licesne No',
                'label_attributes' => array(
                    'class' => 'atpSet',
                ),
            ),
        ));

        $this->add(array(
            'name' => 'atpLicenseExpiration',
            'type' => 'Zend\Form\Element\Date',
            'attributes' => array(
                'placeholder' => 'Enter Phone Number',
                'required' => 'required',
                'class' => 'form-control datepicker atpSet ',
            ),
            'options' => array(
                'label' => 'ATP Licesne Expiration Date',
                'label_attributes' => array(
                    'class' => 'atpSet',
                ),
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
            'name' => 'labsNo',
            'type' => 'Zend\Form\Element\Number',
            'attributes' => array(
                'placeholder' => 'Enter ',
                'required' => 'required',
                'class' => 'form-control atpSet',
            ),
            'options' => array(
                'label' => 'Labs No',
                'label_attributes' => array(
                    'class' => 'atpSet',
                ),
            ),
        ));

        $this->add(array(
            'name' => 'pcsNo_lab',
            'type' => 'Zend\Form\Element\Number',
            'attributes' => array(
                'placeholder' => 'Enter ',
                'required' => 'required',
                'class' => 'form-control atpSet',
            ),
            'options' => array(
                'label' => 'Pcs No / Lab',
                'label_attributes' => array(
                    'class' => 'atpSet',
                ),
            ),
        ));

        $this->add(array(
            'name' => 'internetSpeed_lab',
            'type' => 'Zend\Form\Element\Number',
            'attributes' => array(
                'placeholder' => 'Enter ',
                'required' => 'required',
                'class' => 'form-control atpSet',
            ),
            'options' => array(
                'label' => 'Internet Speed / Lab',
                'label_attributes' => array(
                    'class' => 'atpSet',
                ),
            ),
        ));

        $this->add(array(
            'name' => 'operatingSystem',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
                'placeholder' => 'Enter ',
                'required' => 'required',
                'class' => 'form-control atpSet',
            ),
            'options' => array(
                'label' => 'Operating System',
                'label_attributes' => array(
                    'class' => 'atpSet',
                ),
            ),
        ));

        $this->add(array(
            'name' => 'operatingSystemLang',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
                'placeholder' => 'Enter ',
                'required' => 'required',
                'class' => 'form-control atpSet',
            ),
            'options' => array(
                'label' => 'OperatingSystemLang',
                'label_attributes' => array(
                    'class' => 'atpSet',
                ),
            ),
        ));

        $this->add(array(
            'name' => 'officeVersion',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
                'placeholder' => 'Enter ',
                'required' => 'required',
                'class' => 'form-control atpSet',
            ),
            'options' => array(
                'label' => 'Microseft Office Version',
                'label_attributes' => array(
                    'class' => 'atpLicenseNo atpSet',
                ),
            ),
        ));

        $this->add(array(
            'name' => 'officeLang',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
                'placeholder' => 'Enter ',
                'required' => 'required',
                'class' => 'form-control atpSet',
            ),
            'options' => array(
                'label' => 'Microsoft office Language',
                'label_attributes' => array(
                    'class' => 'atpSet',
                ),
            ),
        ));


//            END OF ATP
///////////////////////////////////////////////////////////////
        //////////// ATC DATA /////////////

        $this->add(array(
            'name' => 'atcLicenseExpiration',
            'type' => 'Zend\Form\Element\Date',
            'attributes' => array(
                'placeholder' => 'Enter Phone Number',
                'required' => 'required',
                'class' => 'form-control datepicker atcSet',
            ),
            'options' => array(
                'label' => 'ATC Licesne Expiration Date',
                'label_attributes' => array(
                    'class' => 'atcSet',
                ),
            ),
        ));
        $this->add(array(
            'name' => 'atcLicenseNo',
            'type' => 'Zend\Form\Element\Number',
            'attributes' => array(
                'placeholder' => 'Enter Phone Number',
                'required' => 'required',
                'class' => 'form-control atcSet',
            ),
            'options' => array(
                'label' => 'ATC Licesne No',
                'label_attributes' => array(
                    'class' => 'atcSet',
                ),
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
            'name' => 'classesNo',
            'type' => 'Zend\Form\Element\Number',
            'attributes' => array(
                'placeholder' => 'Enter ',
                'required' => 'required',
                'class' => 'form-control atcSet',
            ),
            'options' => array(
                'label' => 'Classes No.',
                'label_attributes' => array(
                    'class' => 'atcSet',
                ),
            ),
        ));

        $this->add(array(
            'name' => 'pcsNo_class',
            'type' => 'Zend\Form\Element\Number',
            'attributes' => array(
                'placeholder' => 'Enter ',
                'required' => 'required',
                'class' => 'form-control atcSet',
            ),
            'options' => array(
                'label' => 'Pcs No / Class',
                'label_attributes' => array(
                    'class' => ' atcSet',
                ),
            ),
        ));

//        END OF ATC
//////////////////////////////////////////////////////////////////////

        $this->add(array(
            'name' => 'trainingManager_id',
            'type' => 'DoctrineModule\Form\Element\ObjectSelect',
            'attributes' => array(
                'class' => 'form-control',
                'multiple' => true,
            ),
            'options' => array(
                'label' => 'Training Manager',
                'object_manager' => $this->query->entityManager,
                'target_class' => 'Users\Entity\User',
                'property' => 'firstName',
                'label_generator' => function($targetEntity) {
                    return $targetEntity->getFirstName() . ' ' . $targetEntity->getMiddleName() . ' ' . $targetEntity->getLastName();
                },
            )
        ));

        $this->add(array(
            'name' => 'testCenterAdmin_id',
            'type' => 'DoctrineModule\Form\Element\ObjectSelect',
            'attributes' => array(
                'class' => 'form-control',
                'multiple' => true,
            ),
            'options' => array(
                'label' => 'Test Center Admin',
                'object_manager' => $this->query->entityManager,
                'target_class' => 'Users\Entity\User',
                'property' => 'firstName',
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
                'multiple' => true,
            ),
            'options' => array(
                'label' => 'Focal Contact Person',
                'object_manager' => $this->query->entityManager,
                'target_class' => 'Users\Entity\User',
                'property' => 'firstName',
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
            'name' => 'privacyStatement',
            'type' => 'Zend\Form\Element\Checkbox',
            'attributes' => array(
                'class' => 'form-control',
                'required' => 'required',
            ),
            'options' => array(
                'label' => 'Privacy Statement',
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
//        if()
        $trainingManagerId = $object->getTrainingManager();
        if (isset($trainingManagerId->id) && $trainingManagerId != null) {
            $this->get('trainingManager_id')->setValue($trainingManagerId->id);
        }
        $testCenterAdminId = $object->getTestCenterAdmin();
        if (isset($testCenterAdminId->id) && $testCenterAdminId != null) {
            $this->get('testCenterAdmin_id')->setValue($testCenterAdminId->id);
        }
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
