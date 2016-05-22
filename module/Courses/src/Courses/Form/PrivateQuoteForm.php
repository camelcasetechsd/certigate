<?php

namespace Courses\Form;

use Utilities\Service\Status;
use CustomDoctrine\Service\DoctrineObject as DoctrineHydrator;
use Courses\Form\CourseEventFieldset;
use Courses\Form\QuoteForm;

/**
 * PrivateQuote Form
 * 
 * Handles PrivateQuote form setup
 * 
 * @property Utilities\Service\Query\Query $query
 * 
 * @package courses
 * @subpackage form
 */
class PrivateQuoteForm extends QuoteForm
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
        parent::__construct($name, $options);

        $status = $options['status'];
        $this->query = $options['query'];
        if ($status == Status::STATUS_PENDING_PRICING && $this->isAdminUser === true) {
            // The form will hydrate an object of type "CourseEvent"
            $this->setHydrator(new DoctrineHydrator($this->query->entityManager));
            // Add the course event fieldset
            $courseEventFieldset = new CourseEventFieldset($this->query, $this->isAdminUser, $options['userId'], $options['applicationLocale']);
            $this->add($courseEventFieldset);

            $this->add(array(
                'name' => 'price',
                'type' => 'Zend\Form\Element\Text',
                'attributes' => array(
                    'maxlength' => 7,
                    'placeholder' => 'Price is in US Dollar',
                    'required' => 'required',
                    'class' => 'form-control',
                ),
                'options' => array(
                    'label' => 'Price',
                ),
            ));
        }
        
        $this->initialize($options);
    }

}
