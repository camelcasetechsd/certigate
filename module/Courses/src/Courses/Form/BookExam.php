<?php

namespace Courses\Form;

use Utilities\Form\Form;
use Utilities\Service\Time;
use DoctrineModule\Persistence\ObjectManagerAwareInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Translation\Service\Locale\Locale;

/**
 * BookExam Form
 * 
 * Handles BookExam setup
 * 
 * @property Utilities\Service\Query\Query $query
 * 
 * @package courses
 * @subpackage form
 */
class BookExam extends Form implements ObjectManagerAwareInterface
{

    protected $objectManager;

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
        $locale = $options['applicationLocale']->getCurrentLocale();
        unset($options['applicationLocale']);
        parent::__construct($name, $options);

        $this->setAttribute('class', 'form form-horizontal');
        if ($locale == Locale::LOCALE_AR_AR) {
            $this->add(array(
                'name' => 'dateHj',
                'type' => 'Zend\Form\Element\Date',
                'attributes' => array(
                    'required' => 'required ',
                    'class' => 'form-control hijriDate-ar',
                    'type' => 'text',
                ),
                'options' => array(
                    'label' => 'Hijri Exam Date',
                    'format' => Time::DATE_FORMAT,
                ),
                'validators' => array(
                    'Courses\Form\TenDaysAfterValidator' => true
                )
            ));
            $this->add(array(
                'name' => 'date',
                'type' => 'Zend\Form\Element\Date',
                'attributes' => array(
                    'required' => 'required ',
                    'class' => 'form-control gregorianDate-ar',
                    'type' => 'text',
                ),
                'options' => array(
                    'label' => 'Exam Date',
                    'format' => Time::DATE_FORMAT,
                ),
                'validators' => array(
                    'Courses\Form\TenDaysAfterValidator' => true
                )
            ));
        }
        else {
            $this->add(array(
                'name' => 'dateHj',
                'type' => 'Zend\Form\Element\Date',
                'attributes' => array(
                    'required' => 'required ',
                    'class' => 'form-control hijriDate',
                    'type' => 'text',
                ),
                'options' => array(
                    'label' => 'Hijri Exam Date',
                    'format' => Time::DATE_FORMAT,
                ),
                'validators' => array(
                    'Courses\Form\TenDaysAfterValidator' => true
                )
            ));
            $this->add(array(
                'name' => 'date',
                'type' => 'Zend\Form\Element\Date',
                'attributes' => array(
                    'required' => 'required ',
                    'class' => 'form-control gregorianDate',
                    'type' => 'text',
                ),
                'options' => array(
                    'label' => 'Exam Date',
                    'format' => Time::DATE_FORMAT,
                ),
                'validators' => array(
                    'Courses\Form\TenDaysAfterValidator' => true
                )
            ));
        }

        $this->add(array(
            'name' => 'studentsNo',
            'type' => 'Zend\Form\Element\Number',
            'attributes' => array(
                'required' => 'required',
                'class' => 'form-control ',
            ),
            'options' => array(
                'label' => 'Students Number',
            ),
        ));

        $this->add(array(
            'name' => 'atc',
            'type' => 'DoctrineModule\Form\Element\ObjectSelect',
            'attributes' => array(
                'required' => 'required',
                'class' => 'form-control',
                'multiple' => false,
            ),
            'options' => array(
                'label' => 'Authenticated Test Center',
                'label_attributes' => array(
                    'class' => ' ',
                ),
                'object_manager' => $this->query->entityManager,
                'target_class' => 'Organizations\Entity\Organization',
                'property' => 'type',
                'empty_item_label' => self::EMPTY_SELECT_VALUE,
                'display_empty_item' => true,
                'label_generator' => function($targetEntity) {
            return $targetEntity->getCommercialName();
        },
                'find_method' => array(
                    'name' => 'listOrganizations',
                    'params' => array(
                        'query' => $this->query,
                        'type' => \Organizations\Entity\Organization::TYPE_ATC
                    )
                ),
            )
        ));

        $this->add(array(
            'name' => 'course',
            'type' => 'DoctrineModule\Form\Element\ObjectSelect',
            'attributes' => array(
                'required' => 'required',
                'class' => 'form-control',
                'multiple' => false,
            ),
            'options' => array(
                'label' => 'Course',
                'object_manager' => $this->query->entityManager,
                'target_class' => 'Courses\Entity\Course',
                'empty_item_label' => self::EMPTY_SELECT_VALUE,
                'display_empty_item' => true,
                'property' => 'name',
                'is_method' => false,
            )
        ));

        $this->add(array(
            'name' => 'Create',
            'type' => 'Zend\Form\Element\Submit',
            'attributes' => array(
                'class' => 'btn btn-success',
                'value' => 'Book now!',
            )
        ));
    }

    public function setObjectManager(ObjectManager $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    public function getObjectManager()
    {
        return $this->objectManager;
    }

}
