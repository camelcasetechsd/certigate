<?php

namespace Organizations\Form;

use Utilities\Form\Form;
use Utilities\Form\FormButtons;
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
class TypeForm extends Form
{

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
        $organizationTypes = $this->query->findAll('Organizations\Entity\OrganizationType');
        unset($options['query']);
        parent::__construct($name, $options);

        $this->setAttribute('class', 'form form-horizontal');

        $this->add(array(
            'name' => 'type',
            'required' => true,
            'type' => 'Zend\Form\Element\Hidden',
            'options' => array(
                'messages' => array(
                    \Zend\Validator\NotEmpty::IS_EMPTY => "you have to choose organization type"
                )
            )
        ));

        $valueOptions = array();
        foreach ($organizationTypes as $type) {
            $temp = array(
                'value' => $type->getId(),
                'label' => $type->getTitle().' Organization',
                'checked' => false,
                'attributes' => array(
                    'class' => 'orgType',
                    'id' => 'type-'.$type->getId(),
                ),
            );
            array_push($valueOptions, $temp);
            unset($temp);
        }

        $this->add(array(
            'type' => 'Zend\Form\Element\MultiCheckbox',
            'name' => 'orgType',
            'required' => true,
            'options' => array(
                'label' => 'Organization Type',
                'value_options' => $valueOptions
            ),
        ));


        $this->add(array(
            'name' => FormButtons::SAVE_BUTTON,
            'type' => 'Zend\Form\Element\Submit',
            'attributes' => array(
                'class' => 'btn btn-success',
                'value' => FormButtons::SAVE_BUTTON_TEXT
            )
        ));
    }

}
