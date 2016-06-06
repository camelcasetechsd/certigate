<?php

namespace IssueTracker\Form;

use Utilities\Form\Form;
use Utilities\Form\FormButtons;
class CommentForm extends Form
{

    protected $query;

    public function __construct($name = null, $options = null)
    {
        $this->query = $options['query'];

        unset($options['query']);
        parent::__construct($name, $options);

        $this->setAttribute('class', 'form form-horizontal');

        $this->add(array(
            'name' => 'comment',
            'type' => 'Zend\Form\Element\Textarea',
            'attributes' => array(
                'required' => 'required',
                'class' => 'form-control',
            ),
            'options' => array(
//                'label' => 'Title',
            ),
        ));

        $this->add(array(
            'name' => 'id',
            'type' => 'Zend\Form\Element\Hidden',
        ));

        $this->add(array(
            'name' => FormButtons::SAVE_BUTTON,
            'type' => 'Zend\Form\Element\Submit',
            'attributes' => array(
                'class' => 'btn btn-success',
                'value' => FormButtons::SAVE_BUTTON_TEXT,
            )
        ));
    }

}
