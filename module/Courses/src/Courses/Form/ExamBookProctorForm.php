<?php

namespace Courses\Form;

use Utilities\Form\Form;
use Users\Entity\Role;
use Utilities\Form\ButtonsFieldset;
use Utilities\Service\Status;
use Translation\Service\Locale\Locale;
use Zend\InputFilter\InputFilter;

/**
 * ExamBookProctor Form
 * 
 * Handles ExamBookProctor form setup
 * 
 * @property Utilities\Service\Query\Query $query
 * @property InputFilter $_inputFilter validation constraints 
 * 
 * @package courses
 * @subpackage form
 */
class ExamBookProctorForm extends Form
{

    /**
     *
     * @var Utilities\Service\Query\Query
     */
    public $query;

    /**
     *
     * @var InputFilter validation constraints 
     */
    private $_inputFilter;
    
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
        $applicationLocale = $options['applicationLocale'];
        $currentLocale = $applicationLocale->getCurrentLocale();
        parent::__construct($name, $options);

        $this->setAttribute('class', 'form form-horizontal');

        $this->add(array(
            'name' => 'proctors',
            'type' => 'DoctrineModule\Form\Element\ObjectMultiCheckbox',
            'attributes' => array(
                'class' => 'form-control',
                'class' => 'mar',
            ),
            'options' => array(
                'label' => '',
                'object_manager' => $this->query->entityManager,
                'target_class' => 'Users\Entity\User',
                'property' => 'fullName'.(($currentLocale == Locale::LOCALE_AR_AR)? "Ar":""),
                'is_method' => true,
                'find_method' => array(
                    'name' => 'getUsers',
                    'params' => array(
                        'roles' => array(Role::PROCTOR_ROLE),
                        'status' => Status::STATUS_ACTIVE,
                    )
                ),
                'label_options' => array(
                    'disable_html_escape' => true,
                ),
            ),
        ));

        $this->add(array(
            'name' => 'id',
            'type' => 'Zend\Form\Element\Hidden',
        ));

        // Add buttons fieldset
        $buttonsFieldset = new ButtonsFieldset(/*$name =*/ null, /*$options =*/ array("create_button_only" => true));
        $this->add($buttonsFieldset);
        
        $this->setInputFilter($this->getInputFilter());
    }

    /**
     * set validation constraints
     * 
     * @uses InputFilter
     * 
     * @access public
     * @return InputFilter validation constraints
     */
    public function getInputFilter() {
        if (!$this->_inputFilter) {
            $inputFilter = new InputFilter();
            
            $inputFilter->add(array(
                'name' => 'proctors',
                'required' => true,
            ));

            $this->_inputFilter = $inputFilter;
        }

        return $this->_inputFilter;
    }
}
