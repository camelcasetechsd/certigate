<?php

namespace Utilities\Form;

use Zend\Form\Form as ZendForm;
use Utilities\Service\Inflector;
use Zend\Form\FormInterface;

/**
 * Form
 * 
 * Handles form setup
 * 
 * 
 * 
 * @property bool $isEditForm ,default is bool false
 * 
 * @package utilities
 * @subpackage form
 */
class Form extends ZendForm {

    /**
     * Value appears by default in select fields
     */
    const EMPTY_SELECT_VALUE = "-- Select --";
    
    /**
     *
     * @var bool ,default is false
     */
    public $isEditForm = false;
    
    /**
     * setup form
     * 
     * 
     * @uses \ReflectionClass
     * @uses Inflector
     * 
     * @access public
     * @param string $name ,default is null
     * @param array $options ,default is null
     */
    public function __construct($name = null, $options = null) {
        if(is_null($name)){
            $reflection = new \ReflectionClass($this);
            $inflector = new Inflector();
            $name = $inflector->underscore($reflection->getShortName());
        }
        parent::__construct($name, $options);
    }
    
    /**
     * Bind an object to the form
     *
     * Ensures the object is populated with validated values.
     * Set isEditForm to true so that edit form is distinguished
     * 
     * @param  object $object
     * @param  int $flags ,default value is FormInterface::VALUES_NORMALIZED
     * @param  bool $isEditForm ,default value is true
     * @return mixed|void
     * @throws Exception\InvalidArgumentException
     */
    public function bind($object, $flags = FormInterface::VALUES_NORMALIZED, $isEditForm = true)
    {
        if($isEditForm === true){
            $this->isEditForm = true;
        }
        parent::bind($object, $flags);
    }

}
