<?php

namespace Courses\Form;

use Utilities\Form\Form;
use Utilities\Service\Status;
use Users\Entity\Role;
use Utilities\Service\Time;
use Organizations\Entity\OrganizationType;
use Utilities\Form\ButtonsFieldset;
use Translation\Service\Locale\Locale;

/**
 * CourseEvent Form
 * 
 * Handles CourseEvent form setup
 * 
 * @property Utilities\Service\Query\Query $query
 * @property int $userId
 * @property int $courseId
 * 
 * @package courses
 * @subpackage form
 */
class CourseEventForm extends Form
{

    /**
     *
     * @var Utilities\Service\Query\Query 
     */
    protected $query;

    /**
     *
     * @var int
     */
    protected $userId;

    /**
     *
     * @var int
     */
    protected $courseId;

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
        $this->needAdminApproval = true;
        $this->query = $options['query'];
        unset($options['query']);
        $this->userId = $options['userId'];
        unset($options['userId']);
        $this->courseId = $options['courseId'];
        unset($options['courseId']);
        $locale = $options['applicationLocale']->getCurrentLocale();
        unset($options['applicationLocale']);
        parent::__construct($name, $options);

        $this->setAttribute('class', 'form form-horizontal');

        $status = Status::STATUS_ACTIVE;
        $criteria = array('status' => $status);
        if (empty($this->courseId)) {
            $this->add(array(
                'name' => 'course',
                'type' => 'DoctrineModule\Form\Element\ObjectSelect',
                'attributes' => array(
                    'required' => 'required',
                    'class' => 'form-control',
                ),
                'options' => array(
                    'label' => 'Course',
                    'object_manager' => $this->query->entityManager,
                    'target_class' => 'Courses\Entity\Course',
                    'property' => 'name',
                    'is_method' => false,
                    'find_method' => array(
                        'name' => 'findBy',
                        'params' => array(
                            'criteria' => $criteria
                        )
                    ),
                    'empty_item_label' => self::EMPTY_SELECT_VALUE,
                    'display_empty_item' => true,
                ),
            ));
        }

        $types = array(OrganizationType::TYPE_ATP_TITLE);
        $userIds = array();
        if ($this->isAdminUser === false) {
            $userIds[] = $this->userId;
        }
        $this->add(array(
            'name' => 'atp',
            'type' => 'DoctrineModule\Form\Element\ObjectSelect',
            'attributes' => array(
                'required' => 'required',
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => 'Training Partner',
                'object_manager' => $this->query->entityManager,
                'target_class' => 'Organizations\Entity\Organization',
                'property' => 'commercialName',
                'is_method' => false,
                'find_method' => array(
                    'name' => 'getOrganizationsBy',
                    'params' => array(
                        'userIds' => $userIds,
                        'types' => $types,
                        'status' => $status,
                    )
                ),
                'empty_item_label' => self::EMPTY_SELECT_VALUE,
                'display_empty_item' => true,
            ),
        ));
        $this->add(array(
            'name' => 'ai',
            'type' => 'DoctrineModule\Form\Element\ObjectSelect',
            'attributes' => array(
                'required' => 'required',
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => 'Instructor',
                'object_manager' => $this->query->entityManager,
                'target_class' => 'Users\Entity\User',
                'property' => 'fullName',
                'is_method' => true,
                'find_method' => array(
                    'name' => 'getUsers',
                    'params' => array(
                        "roles" => array(
                            Role::INSTRUCTOR_ROLE
                        )
                    )
                ),
                'empty_item_label' => self::EMPTY_SELECT_VALUE,
                'display_empty_item' => true,
            ),
        ));

        $this->add(array(
            'name' => 'capacity',
            'type' => 'Zend\Form\Element\Number',
            'attributes' => array(
                'required' => 'required',
                'class' => 'form-control',
                'min' => '1',
            ),
            'options' => array(
                'label' => 'Capacity',
            ),
        ));

        $this->add(array(
            'name' => 'studentsNo',
            'type' => 'Zend\Form\Element\Number',
            'attributes' => array(
                'required' => 'required',
                'class' => 'form-control',
                'min' => '0',
            ),
            'options' => array(
                'label' => 'Students Number',
            ),
        ));

        $this->add(array(
            'name' => "fullCapacity",
            'type' => 'Zend\Form\Element',
            'attributes' => array(
                'class' => 'btn btn-primary',
                'value' => "Full Capacity",
                'type' => 'button',
                'onclick' => 'setFullCapacity("#course_event_form_studentsNo","#course_event_form_capacity")',
            )
        ));
        if ($locale == Locale::LOCALE_AR_AR) {

            $this->add(array(
                'name' => 'startDateHj',
                'type' => 'Zend\Form\Element\Date',
                'attributes' => array(
                    'required' => 'required',
                    'class' => 'form-control hijriDate-ar',
                    'type' => 'text',
                ),
                'options' => array(
                    'label' => 'Hijri Start Date',
                    'format' => Time::DATE_FORMAT,
                ),
            ));
            $this->add(array(
                'name' => 'startDate',
                'type' => 'Zend\Form\Element\Date',
                'attributes' => array(
                    'required' => 'required',
                    'class' => 'form-control gregorianDate-ar',
                    'type' => 'text',
                ),
                'options' => array(
                    'label' => 'Start Date',
                    'format' => Time::DATE_FORMAT,
                ),
            ));
            $this->add(array(
                'name' => 'endDateHj',
                'type' => 'Zend\Form\Element\Date',
                'attributes' => array(
                    'required' => 'required',
                    'class' => 'form-control hijriDate-ar',
                    'type' => 'text',
                ),
                'options' => array(
                    'label' => 'Hijri End Date',
                    'format' => Time::DATE_FORMAT,
                ),
            ));
            $this->add(array(
                'name' => 'endDate',
                'type' => 'Zend\Form\Element\Date',
                'attributes' => array(
                    'required' => 'required',
                    'class' => 'form-control gregorianDate-ar',
                    'type' => 'text',
                ),
                'options' => array(
                    'label' => 'End Date',
                    'format' => Time::DATE_FORMAT,
                ),
            ));
        }
        else {

            $this->add(array(
                'name' => 'startDateHj',
                'type' => 'Zend\Form\Element\Date',
                'attributes' => array(
                    'required' => 'required',
                    'class' => 'form-control hijriDate',
                    'type' => 'text',
                ),
                'options' => array(
                    'label' => 'Hijri Start Date',
                    'format' => Time::DATE_FORMAT,
                ),
            ));
            $this->add(array(
                'name' => 'startDate',
                'type' => 'Zend\Form\Element\Date',
                'attributes' => array(
                    'required' => 'required',
                    'class' => 'form-control gregorianDate',
                    'type' => 'text',
                ),
                'options' => array(
                    'label' => 'Start Date',
                    'format' => Time::DATE_FORMAT,
                ),
            ));
            $this->add(array(
                'name' => 'endDateHj',
                'type' => 'Zend\Form\Element\Date',
                'attributes' => array(
                    'required' => 'required',
                    'class' => 'form-control hijriDate',
                    'type' => 'text',
                ),
                'options' => array(
                    'label' => 'Hijri End Date',
                    'format' => Time::DATE_FORMAT,
                ),
            ));
            $this->add(array(
                'name' => 'endDate',
                'type' => 'Zend\Form\Element\Date',
                'attributes' => array(
                    'required' => 'required',
                    'class' => 'form-control gregorianDate',
                    'type' => 'text',
                ),
                'options' => array(
                    'label' => 'End Date',
                    'format' => Time::DATE_FORMAT,
                ),
            ));
        }

        $this->add(array(
            'name' => 'hideFromCalendar',
            'type' => 'Zend\Form\Element\Checkbox',
            'attributes' => array(
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => 'Hide From Calendar',
                'checked_value' => Status::STATUS_ACTIVE,
                'unchecked_value' => Status::STATUS_INACTIVE
            ),
        ));

        $this->add(array(
            'name' => 'id',
            'type' => 'Zend\Form\Element\Hidden',
        ));
        // Add buttons fieldset
        $buttonsFieldset = new ButtonsFieldset(/* $name = */ null, /* $options = */ array("save_button_only" => true));
        $this->add($buttonsFieldset);
    }

}
