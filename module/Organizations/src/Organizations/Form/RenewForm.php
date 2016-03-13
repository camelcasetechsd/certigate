<?php

namespace Organizations\Form;

use Utilities\Form\Form;
use Utilities\Service\Time;
use Zend\Form\FormInterface;

/**
 * OrganizationUser Form
 * 
 * Handles OrganizationUser form setup
 * 
 * @property Utilities\Service\Query\Query $query
 * @property int $organizationType
 * 
 * @package organizations
 * @subpackage form
 */
class RenewForm extends Form
{

    /**
     *
     * @var Utilities\Service\Query\Query
     */
    public $query;

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

        /**
         * Atp Renewal fields
         */
        $this->add(array(
            'name' => 'atpLicenseNo',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
                'placeholder' => 'Enter License',
                'class' => 'form-control ',
            ),
            'options' => array(
                'label' => 'ATP License',
                'label_attributes' => array(
                ),
            ),
        ));

        $this->add(array(
            'name' => 'atpLicenseExpiration',
            'type' => 'Zend\Form\Element\Date',
            'attributes' => array(
                'placeholder' => 'Enter License Expiration Date',
                'class' => 'form-control',
                'type' => 'text',
            ),
            'options' => array(
                'label' => 'ATP License Expiration Date',
                'label_attributes' => array(
                ),
                'format' => Time::DATE_FORMAT,
            ),
        ));

        $this->add(array(
            'name' => 'atpLicenseAttachment',
            'type' => 'Zend\Form\Element\File',
            'attributes' => array(
                'class' => 'form-control ',
            ),
            'options' => array(
                'label' => 'ATP License Attachment',
                'label_attributes' => array(
                ),
            ),
        ));

        /**
         * Atc Renewal Fields
         */
        $this->add(array(
            'name' => 'atcLicenseNo',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
                'placeholder' => 'Enter License',
                'class' => 'form-control ',
            ),
            'options' => array(
                'label' => 'ATC License',
                'label_attributes' => array(
                ),
            ),
        ));

        $this->add(array(
            'name' => 'atcLicenseExpiration',
            'type' => 'Zend\Form\Element\Date',
            'attributes' => array(
                'placeholder' => 'Enter License Expiration Date',
                'class' => 'form-control',
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
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => 'ATC License Attachment',
                'label_attributes' => array(
                ),
            ),
        ));

        /**
         * Wire Transfer
         */
        $this->add(array(
            'name' => 'wireTransferAttachment',
            'type' => 'Zend\Form\Element\File',
            'options' => array(
                'label' => 'Wire Transfer Attachment',
            ),
            'attributes' => array(
                'class' => 'form-control',
            )
        ));

        $this->add(array(
            'name' => 'Renew',
            'type' => 'Zend\Form\Element\Submit',
            'attributes' => array(
                'class' => 'btn btn-success',
                'value' => 'Add',
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
    }

}
