<?php

namespace Courses\Form;

use Utilities\Service\Status;
use Courses\Form\QuoteForm;

/**
 * PublicQuote Form
 * 
 * Handles PublicQuote form setup
 * 
 * @package courses
 * @subpackage form
 */
class PublicQuoteForm extends QuoteForm
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

        $status = $options['status'];
        if ($status == Status::STATUS_PENDING_PRICING && $this->isAdminUser === true) {
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
        }
        
        $this->initialize($options);
    }

}
