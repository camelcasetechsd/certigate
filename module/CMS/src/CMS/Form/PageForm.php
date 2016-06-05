<?php

namespace CMS\Form;

use Utilities\Form\Form;
use CMS\Service\PageTypes;
use CMS\Service\PageCategories;
use Utilities\Form\FormButtons;
use Translation\Service\Locale\Locale;
use Utilities\Form\ButtonsFieldset;

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
     *
     * @var Translation\Service\Locale\Locale 
     */
    protected $locale;

    /**
     *
     * @var Translation\Helper\translatorHandler 
     */
    protected $translatorHandler;

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
        $this->translatorHandler = $options['translatorHandler'];
        $buttonFieldSetOptions = array();
        if (isset($options['unpublishedFlag'])) {
            $buttonFieldSetOptions = array(
                'unpublishedFlag' => $options['unpublishedFlag']
            );
        }
        unset($options['query']);
        unset($options['locale']);
        unset($options['translatorHandler']);
        parent::__construct($name, $options);

        $this->setAttribute('class', 'form form-horizontal');

        $this->add(array(
            'name' => 'title',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
                'required' => 'required',
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => 'Title',
            ),
        ));
        $this->add(array(
            'name' => 'titleAr',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
                'required' => 'required',
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => 'Title in Arabic',
            ),
        ));

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
                    PageTypes::PAGE_TYPE => $this->translatorHandler->translate(PageTypes::PAGE_TYPE),
                    PageTypes::PRESS_RELEASE_TYPE => $this->translatorHandler->translate(PageTypes::PRESS_RELEASE_TYPE)
                ),
                'empty_option' => $this->translatorHandler->translate(self::EMPTY_SELECT_VALUE),
            ),
        ));

        $this->add(array(
            'name' => 'path',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
                'required' => 'required',
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => 'Path',
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
                    $this->translatorHandler->translate(PageCategories::DEFAULT_CATEGORY) => $this->translatorHandler->translate(PageCategories::DEFAULT_CATEGORY),
                ),
                'empty_option' => $this->translatorHandler->translate(self::EMPTY_SELECT_VALUE),
            ),
        ));

        $this->add(array(
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

        $this->add(array(
            'name' => 'summary',
            'type' => 'Zend\Form\Element\Textarea',
            'attributes' => array(
                'required' => 'required',
                'class' => 'form-control',
                'maxlength' => 1000,
                'cols' => 80,
            ),
            'options' => array(
                'label' => 'Summary',
            ),
        ));

        $this->add(array(
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

        $this->add(array(
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
        ));

        $this->add(array(
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
        ));

        $this->add(array(
            'name' => 'id',
            'type' => 'Zend\Form\Element\Hidden',
        ));

        $buttonsFieldset = new ButtonsFieldset(/* $name = */ null, /* $options = */ $buttonFieldSetOptions);
        $this->add($buttonsFieldset);
    }

}
