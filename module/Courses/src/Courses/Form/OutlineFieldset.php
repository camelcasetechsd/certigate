<?php

namespace Courses\Form;

use Utilities\Service\Status;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Courses\Entity\Outline;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;

/**
 * Outline Fieldset
 * 
 * Handles Outline form setup
 * 
 * @property Utilities\Service\Query\Query $query
 * @property bool $isAdminUser
 * 
 * @package courses
 * @subpackage form
 */
class OutlineFieldset extends Fieldset implements InputFilterProviderInterface
{

    /**
     *
     * @var Utilities\Service\Query\Query 
     */
    protected $query;

    /**
     *
     * @var bool
     */
    protected $isAdminUser;
    
    /**
     * setup form
     * 
     * 
     * @access public
     * @param Utilities\Service\Query\Query  $query
     * @param bool $isAdminUser
     */
    public function __construct($query, $isAdminUser)
    {
        $this->query = $query;
        $this->isAdminUser = $isAdminUser;
        parent::__construct(/*$name =*/ "outline");

        $this->setHydrator(new DoctrineHydrator($this->query->entityManager))
             ->setObject(new Outline())
         ;
        
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
            'name' => 'duration',
            'type' => 'Zend\Form\Element\Number',
            'attributes' => array(
                'placeholder' => 'Duration is in minutes',
                'required' => 'required',
                'class' => 'form-control',
                'min' => '1',
            ),
            'options' => array(
                'label' => 'Duration',
            ),
        ));

        if ($this->isAdminUser === true) {
            $this->add(array(
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
            ));
        }
        $this->add(array(
            'name' => 'id',
            'type' => 'Zend\Form\Element\Hidden',
        ));
    }

    /**
     * Get inputfilter specification
     * 
     * @access public
     * @return array
     */
    public function getInputFilterSpecification()
    {
        return array(
            'title' => array(
                'required' => true,
            ),
            'duration' => array(
                'required' => true,
            ),
        );
    }

}
