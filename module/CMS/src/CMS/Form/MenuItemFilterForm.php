<?php

namespace CMS\Form;

use Utilities\Form\Form;
use Utilities\Service\Status;

/**
 * MenuItemFilter Form
 * 
 * Handles MenuItem filter
 * 
 * @property Utilities\Service\Query\Query $query
 * 
 * @package cms
 * @subpackage form
 */
class MenuItemFilterForm extends Form
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
        unset($options['query']);
        parent::__construct($name, $options);
        $this->setAttribute('class', 'form form-inline');
        $this->setAttribute('method', 'GET');

        $this->add(array(
            'name' => 'title',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => 'Title',
            ),
        ));

        $this->add(array(
            'name' => 'directUrl',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => 'Direct Url',
            ),
        ));

        $this->add(array(
            'name' => 'menu',
            'type' => 'DoctrineModule\Form\Element\ObjectSelect',
            'attributes' => array(
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => 'Menu',
                'object_manager' => $this->query->setEntity( 'CMS\Entity\Menu')->entityManager,
                'target_class' => 'CMS\Entity\Menu',
                'property' => "title",
                'find_method' => array(
                    'name' => 'findAll',
                    'params' => array(
                    )
                ),
                'display_empty_item' => true,
                'empty_item_label' => "All",
            ),
        ));

        $this->add(array(
            'name' => 'status',
            'type' => 'Zend\Form\Element\Select',
            'attributes' => array(
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => 'Status',
                "value_options" => array(
                    Status::STATUS_ACTIVE => Status::STATUS_ACTIVE_TEXT,
                    Status::STATUS_INACTIVE => Status::STATUS_INACTIVE_TEXT
                ),
                'empty_option' => "All",
            ),
        ));


        $this->add(array(
            'name' => 'filter',
            'type' => 'Zend\Form\Element\Submit',
            'attributes' => array(
                'class' => 'btn btn-success',
                'value' => 'Filter',
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

}
