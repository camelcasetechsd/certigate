<?php

namespace Courses\Form;

use Utilities\Form\Form;
use Utilities\Form\FormButtons;

/**
 * QuoteFilter Form
 * 
 * Handles Quote filter
 * 
 * @property Utilities\Service\Query\Query $quoteModel
 * 
 * @package courses
 * @subpackage form
 */
class QuoteFilterForm extends Form
{

    /**
     *
     * @var Utilities\Service\Query\Query 
     */
    protected $quoteModel;

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
        $this->quoteModel = $options['quoteModel'];
        unset($options['quoteModel']);
        parent::__construct($name, $options);
        $this->setAttribute('class', 'form form-inline');
        $this->setAttribute('method', 'GET');

        $this->add(array(
            'name' => 'type',
            'type' => 'Zend\Form\Element\Select',
            'attributes' => array(
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => 'Type',
                'value_options' => $this->quoteModel->getTranslatedQuoteTypes(),
            ),
        ));

        $this->add(array(
            'name' => FormButtons::FILTER_BUTTON,
            'type' => 'Zend\Form\Element\Submit',
            'attributes' => array(
                'class' => 'btn btn-success',
                'value' => FormButtons::FILTER_BUTTON_TEXT,
            )
        ));

        $this->add(array(
            'name' => FormButtons::RESET_BUTTON,
            'type' => 'Zend\Form\Element',
            'attributes' => array(
                'class' => 'btn btn-danger resetButton',
                'value' => FormButtons::RESET_BUTTON_TEXT,
                'type' => 'button',
            )
        ));
    }

}
