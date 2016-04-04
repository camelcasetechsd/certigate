<?php

namespace Courses\Form;

use Utilities\Form\Form;
use Utilities\Service\Status;
use Utilities\Form\FormButtons;

/**
 * PublicQuote Form
 * 
 * Handles PublicQuote form setup
 * 
 * @package courses
 * @subpackage form
 */
class PublicQuoteForm extends Form
{

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
        parent::__construct($name, $options);
        $this->setAttribute('class', 'form form-horizontal');

        $status = $options['status'];
        if ($status == Status::STATUS_PENDING_PRICING) {
            $this->add(array(
                'name' => 'unitPrice',
                'type' => 'Zend\Form\Element\Text',
                'attributes' => array(
                    'maxlength' => 7,
                    'placeholder' => 'Price is in US Dollar',
                    'required' => 'required',
                    'class' => 'form-control',
                ),
                'options' => array(
                    'label' => 'Unit Price',
                ),
            ));
            $this->add(array(
                'name' => 'discount',
                'type' => 'Zend\Form\Element\Text',
                'attributes' => array(
                    'maxlength' => 7,
                    'placeholder' => 'Discount is in US Dollar',
                    'required' => 'required',
                    'class' => 'form-control',
                ),
                'options' => array(
                    'label' => 'Discount',
                ),
            ));
        }
        if ($status == Status::STATUS_PENDING_PAYMENT || $status == Status::STATUS_PENDING_REPAYMENT) {
            $this->add(array(
                'name' => 'wireTransfer',
                'type' => 'Zend\Form\Element\File',
                'attributes' => array(
                    'accept' => 'image/*,application/zip,application/octet-stream,application/pdf,',
                ),
                'options' => array(
                    'label' => '<p class="required">File</p> <p>Supported Extensions: zip,pdf,gif,png,jpg,jpeg</p>',
                    'label_options' => array(
                        'disable_html_escape' => true,
                    )
                ),
            ));
        }

        $this->add(array(
            'name' => 'id',
            'type' => 'Zend\Form\Element\Hidden',
        ));

        if ($status == Status::STATUS_PENDING_REVIEW) {
            $this->add(array(
                'name' => FormButtons::PROCESS_BUTTON,
                'type' => 'Zend\Form\Element\Submit',
                'attributes' => array(
                    'class' => 'pull-left btn-inline btn btn-success',
                    'value' => FormButtons::PROCESS_BUTTON_TEXT,
                )
            ));
        }
        if ($status == Status::STATUS_PENDING_PRICING || $status == Status::STATUS_PENDING_PAYMENT || $status == Status::STATUS_PENDING_REPAYMENT) {
            $this->add(array(
                'name' => FormButtons::ACCEPT_BUTTON,
                'type' => 'Zend\Form\Element\Submit',
                'attributes' => array(
                    'class' => 'pull-left btn-inline btn btn-success',
                    'value' => FormButtons::ACCEPT_BUTTON_TEXT,
                )
            ));
            $this->add(array(
                'name' => FormButtons::DECLINE_BUTTON,
                'type' => 'Zend\Form\Element\Submit',
                'attributes' => array(
                    'class' => 'pull-left btn-inline btn btn-success',
                    'value' => FormButtons::DECLINE_BUTTON_TEXT,
                )
            ));
        }
        
        if ($this->isAdminUser === true) {
            $this->add(array(
                'name' => FormButtons::CANCEL_BUTTON,
                'type' => 'Zend\Form\Element\Submit',
                'attributes' => array(
                    'class' => 'pull-left btn-inline btn btn-danger',
                    'value' => FormButtons::CANCEL_BUTTON_TEXT,
                )
            ));
        }
    }

}
