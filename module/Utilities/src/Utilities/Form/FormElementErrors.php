<?php

namespace Utilities\Form;

use Zend\Form\View\Helper\FormElementErrors as OriginalFormElementErrors;

/**
 * FormElementErrors
 * 
 * Handles form errors display
 * 
 * 
 * 
 * @property string $messageCloseString
 * @property string $messageOpenFormat
 * @property string $messageSeparatorString
 * 
 * @package utilities
 * @subpackage form
 */
class FormElementErrors extends OriginalFormElementErrors  
{
    /**
     *
     * @var string 
     */
    protected $messageCloseString     = '</li></ul>';
    
    /**
     *
     * @var string 
     */
    protected $messageOpenFormat      = '<ul%s><li class="errors">';
    
    /**
     *
     * @var string 
     */
    protected $messageSeparatorString = '</li><li class="errors">';
}
