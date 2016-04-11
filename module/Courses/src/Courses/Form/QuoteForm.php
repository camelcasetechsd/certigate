<?php

namespace Courses\Form;

use Utilities\Form\Form;
use Utilities\Service\Status;
use Utilities\Form\FormButtons;

/**
 * Quote Form
 * 
 * Handles Quote form setup
 * 
 * @package courses
 * @subpackage form
 */
class QuoteForm extends Form
{

    /**
     * setup form
     * 
     * 
     * @access public
     * @param array $options ,default is null
     */
    public function initialize($options = null)
    {
        $this->setAttribute('class', 'form form-horizontal');

        $status = $options['status'];
        if ($status == Status::STATUS_PENDING_PRICING && $this->isAdminUser === true) {
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
        if ($this->isAdminUser === false && ($status == Status::STATUS_PENDING_PAYMENT || $status == Status::STATUS_PENDING_REPAYMENT)) {
            $this->add(array(
                'name' => 'wireTransfer',
                'type' => 'Zend\Form\Element\File',
                'attributes' => array(
                    'accept' => 'image/*,application/zip,application/octet-stream,application/pdf,',
                ),
                'options' => array(
                    'label' => '<p class="required">Wire Transfer</p> <p>Supported Extensions: zip,pdf,gif,png,jpg,jpeg</p>',
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

        if ($status == Status::STATUS_PENDING_PRICING && $this->isAdminUser === true) {
            $this->add(array(
                'name' => FormButtons::PROCESS_BUTTON,
                'type' => 'Zend\Form\Element\Submit',
                'attributes' => array(
                    'class' => 'pull-left btn-inline btn btn-success',
                    'value' => FormButtons::PROCESS_BUTTON_TEXT,
                )
            ));
        }

        if (($this->isAdminUser === true && $status == Status::STATUS_PENDING_REVIEW )
                || ($this->isAdminUser === false && ($status == Status::STATUS_PENDING_PAYMENT || $status == Status::STATUS_PENDING_REPAYMENT))) {
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
                    'class' => 'pull-left btn-inline btn btn-danger',
                    'value' => FormButtons::DECLINE_BUTTON_TEXT,
                    'formnovalidate' => true,
                )
            ));
        }
        
        if ($this->isAdminUser === true && ($status == Status::STATUS_PENDING_PRICING || $status == Status::STATUS_PENDING_REVIEW)) {
            $this->add(array(
                'name' => FormButtons::CANCEL_BUTTON,
                'type' => 'Zend\Form\Element\Submit',
                'attributes' => array(
                    'class' => 'pull-left btn-inline btn btn-danger',
                    'value' => FormButtons::CANCEL_BUTTON_TEXT,
                    'formnovalidate' => true,
                )
            ));
        }
    }

}
