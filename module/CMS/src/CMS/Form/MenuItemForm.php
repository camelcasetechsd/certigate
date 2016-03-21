<?php

namespace CMS\Form;

use Utilities\Form\Form;
use Utilities\Service\Status;
use CMS\Entity\MenuItem;

/**
 * MenuItem Form
 * 
 * Handles MenuItem form setup
 * 
 * @property Utilities\Service\Query\Query $query
 * 
 * @package cms
 * @subpackage form
 */
class MenuItemForm extends Form
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
    public function __construct( $name = null, $options = null )
    {
        $this->query = $options['query'];
        unset( $options['query'] );
        parent::__construct( $name, $options );
        $hiddenMenuItemsIds = array();
        if (isset( $options['hiddenMenuItemsIds'] )) {
            $hiddenMenuItemsIds = $options['hiddenMenuItemsIds'];
        }
        $this->setAttribute( 'class', 'form form-horizontal' );

        $this->add( array(
            'name' => 'title',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
                'required' => 'required',
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => 'Title',
            ),
        ) );

        $this->add( array(
            'name' => 'titleAr',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
                'required' => 'required',
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => 'Title in Arabic',
            ),
        ) );

        $this->add( array(
            'type' => 'Zend\Form\Element\Radio',
            'name' => 'type',
            'options' => array(
                'label' => '<div class="required">Menu Item Type</div>',
                'label_options' => array(
                    'disable_html_escape' => true,
                ),
                'value_options' => array(
                    array(
                        'value' => MenuItem::TYPE_PAGE,
                        'label' => 'Page',
                        'checked' => false,
                        'attributes' => array(
                            'class' => 'menu_item_type',
                            'id' => 'type-page',
                        ),
                    ),
                    array(
                        'value' => MenuItem::TYPE_DIRECT_URL,
                        'label' => 'Direct Url',
                        'checked' => false,
                        'attributes' => array(
                            'class' => 'menu_item_type',
                            'id' => 'type-directUrl',
                        ),
                    ),
                ),
            ),
        ) );

        $this->add( array(
            'name' => 'directUrl',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
                'class' => 'form-control',
                'required' => 'required',
            ),
            'options' => array(
                'label' => 'Direct Url',
            ),
        ) );

        $this->add( array(
            'name' => 'page',
            'type' => 'DoctrineModule\Form\Element\ObjectSelect',
            'attributes' => array(
                'class' => 'form-control',
                'required' => 'required',
            ),
            'options' => array(
                'label' => 'Page',
                'object_manager' => $this->query->entityManager,
                'target_class' => 'CMS\Entity\Page',
                'property' => 'name',
                'empty_item_label' => self::EMPTY_SELECT_VALUE,
                'display_empty_item' => true,
                'label_generator' => function($targetEntity) {
                    return $targetEntity->getTitle();
                },
            ),
        ) );

        $this->add( array(
            'name' => 'parent',
            'type' => 'DoctrineModule\Form\Element\ObjectSelect',
            'attributes' => array(
                'class' => 'form-control',
                'required' => 'required',
            ),
            'options' => array(
                'label' => 'Parent',
                'object_manager' => $this->query->entityManager,
                'target_class' => 'CMS\Entity\MenuItem',
                'label_generator' => function($targetEntity) {
                    return $targetEntity->getNestedTitle();
                },
                'find_method' => array(
                    'name' => 'getMenuItemsSorted',
                    'params' => array(
                        'hiddenMenuItemsIds' => $hiddenMenuItemsIds
                    )
                ),
                'display_empty_item' => true,
                'empty_item_label' => "- - ",
            ),
        ) );

        $this->add( array(
            'name' => 'weight',
            'type' => 'Zend\Form\Element\Number',
            'attributes' => array(
                'placeholder' => 'Lower value will be displayed at the Top',
                'required' => 'required',
                'class' => 'form-control',
                'min' => '1',
            ),
            'options' => array(
                'label' => 'Sort Order',
            ),
        ) );

        $this->add( array(
            'name' => 'status',
            'type' => 'Zend\Form\Element\Checkbox',
            'attributes' => array(
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => 'Status',
                'checked_value' => Status::STATUS_ACTIVE,
                'unchecked_value' => Status::STATUS_INACTIVE
            ),
        ) );

        $this->add( array(
            'name' => 'menu',
            'type' => 'Zend\Form\Element\Hidden',
        ) );

        $this->add( array(
            'name' => 'id',
            'type' => 'Zend\Form\Element\Hidden',
        ) );

        $this->add( array(
            'name' => 'Create',
            'type' => 'Zend\Form\Element\Submit',
            'attributes' => array(
                'class' => 'btn btn-success',
                'value' => 'Create',
            )
        ) );

        $this->add( array(
            'name' => 'reset',
            'type' => 'Zend\Form\Element',
            'attributes' => array(
                'class' => 'btn btn-danger resetButton',
                'value' => 'Reset',
                'type' => 'button',
            )
        ) );
    }

}
