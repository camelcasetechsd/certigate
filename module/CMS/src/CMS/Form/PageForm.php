<?php

namespace CMS\Form;

use Utilities\Form\Form;
use CMS\Service\PageTypes;
use CMS\Service\PageCategories;
use Utilities\Form\FormButtons;

/**
 * Page Form
 * 
 * Handles Page form setup
 * 
 * @property Utilities\Service\Query\Query $query
 * 
 * @package cms
 * @subpackage form
 */
class PageForm extends Form
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
            'name' => 'path',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
                'required' => 'required',
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => 'Path',
            ),
        ) );
        
        $this->add(array(
            'name' => 'type',
            'type' => 'Zend\Form\Element\Select',
            'attributes' => array(
                'class' => 'form-control',
                'required' => 'required',
            ),
            'options' => array(
                'label' => 'Type',
                "value_options" => array(
                    PageTypes::PAGE_TYPE => PageTypes::PAGE_TYPE,
                    PageTypes::PRESS_RELEASE_TYPE => PageTypes::PRESS_RELEASE_TYPE,
                ),
                'empty_option' => self::EMPTY_SELECT_VALUE,
            ),
        ));
        
        $this->add(array(
            'name' => 'category',
            'type' => 'Zend\Form\Element\Select',
            'attributes' => array(
                'class' => 'form-control',
                'required' => 'required',
            ),
            'options' => array(
                'label' => 'Category',
                "value_options" => array(
                    PageCategories::DEFAULT_CATEGORY => PageCategories::DEFAULT_CATEGORY,
                ),
                'empty_option' => self::EMPTY_SELECT_VALUE,
            ),
        ));
        
        $this->add( array(
            'name' => 'author',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
                'required' => 'required',
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => 'Author',
            ),
        ));
        
        $this->add(array(
            'name' => 'picture',
            'type' => 'Zend\Form\Element\File',
            'attributes' => array(
                'accept' => 'image/*',
            ),
            'options' => array(
                'label' => '<p class="required">Picture</p> <p>Supported Extensions: gif,png,jpg,jpeg</p>',
                'label_options' => array(
                    'disable_html_escape' => true,
                )
            ),
        ));
        
        $this->add( array(
            'name' => 'summary',
            'type' => 'Zend\Form\Element\Textarea',
            'attributes' => array(
                'required' => 'required',
                'class' => 'form-control',
                'rows' => 3,
                'cols' => 80,
            ),
            'options' => array(
                'label' => 'Summary',
            ),
        ));
        $this->add( array(
            'name' => 'summaryAr',
            'type' => 'Zend\Form\Element\Textarea',
            'attributes' => array(
                'class' => 'form-control',
                'rows' => 3,
                'cols' => 80,
            ),
            'options' => array(
                'label' => 'Summary in Arabic',
            ),
        ));
        
        $this->add( array(
            'name' => 'body',
            'type' => 'Zend\Form\Element\Textarea',
            'attributes' => array(
                'required' => 'required',
                'class' => 'form-control',
                'rows' => 10,
                'cols' => 80,
            ),
            'options' => array(
                'label' => 'Body',
            ),
        ) );
        
        $this->add( array(
            'name' => 'bodyAr',
            'type' => 'Zend\Form\Element\Textarea',
            'attributes' => array(
                'required' => 'required',
                'class' => 'form-control',
                'rows' => 10,
                'cols' => 80,
            ),
            'options' => array(
                'label' => 'Body in Arabic',
            ),
        ) );
        
        $this->add( array(
            'name' => 'id',
            'type' => 'Zend\Form\Element\Hidden',
        ) );
        
        $this->add( array(
            'name' => FormButtons::SAVE_BUTTON,
            'type' => 'Zend\Form\Element\Submit',
            'attributes' => array(
                'class' => 'pull-left btn-inline btn btn-primary',
                'value' => FormButtons::SAVE_BUTTON_TEXT,
            )
        ) );
        
        $this->add( array(
            'name' => FormButtons::SAVE_AND_PUBLISH_BUTTON,
            'type' => 'Zend\Form\Element\Submit',
            'attributes' => array(
                'class' => 'pull-left btn-inline btn btn-success',
                'value' => FormButtons::SAVE_AND_PUBLISH_BUTTON_TEXT,
            )
        ) );
        
        $this->add( array(
            'name' => FormButtons::UNPUBLISH_BUTTON,
            'type' => 'Zend\Form\Element\Submit',
            'attributes' => array(
                'class' => 'pull-left btn-inline btn btn-warning',
                'value' => FormButtons::UNPUBLISH_BUTTON_TEXT,
            )
        ) );

        $this->add( array(
            'name' => 'reset',
            'type' => 'Zend\Form\Element',
            'attributes' => array(
                'class' => 'pull-left btn-inline btn btn-danger resetButton',
                'value' => 'Reset',
                'type' => 'button',
            )
        ) );
    }

}
