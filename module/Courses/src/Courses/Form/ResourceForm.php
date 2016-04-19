<?php

namespace Courses\Form;

use Utilities\Form\Form;
use Utilities\Service\Status;
use Courses\Entity\Resource;
use Zend\Form\FormInterface;
use Utilities\Form\ButtonsFieldset;

/**
 * Resource Form
 * 
 * Handles Resource form setup
 * 
 * @property Utilities\Service\Query\Query $query
 * @property Translation\Helper\TranslatorHelper $translatorHandler
 * @property Courses\Model\Resource $resourceModel
 * @property int $courseId
 * 
 * @package courses
 * @subpackage form
 */
class ResourceForm extends Form
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
    protected $courseId;

    /**
     *
     * @var Translation\Helper\TranslatorHelper
     */
    protected $translatorHandler;

    /**
     *
     * @var Courses\Model\Resource
     */
    protected $resourceModel;

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
        $this->translatorHandler = $options['translatorHandler'];
        $this->resourceModel = $options['resourceModel'];
        $this->courseId = $options['courseId'];

        unset($options['query']);
        unset($options['translatorHandler']);
        unset($options['resourceModel']);
        unset($options['courseId']);
        parent::__construct($name, $options);

        $this->setAttribute('class', 'form form-horizontal');

        $criteria = array();
        $readOnly = false;
        $displayEmptyItem = true;
        if (!empty($this->courseId)) {
            $criteria["id"] = $this->courseId;
            $readOnly = true;
            $displayEmptyItem = false;
        }
        $this->add(array(
            'name' => 'course',
            'type' => 'DoctrineModule\Form\Element\ObjectSelect',
            'attributes' => array(
                'class' => 'form-control',
                'required' => 'required',
                'disabled' => $readOnly,
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
                        "criteria" => $criteria
                    )
                ),
                'empty_item_label' => self::EMPTY_SELECT_VALUE,
                'display_empty_item' => $displayEmptyItem,
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
                'value_options' => $this->resourceModel->getTranslatedResourceTypes(),
                'empty_option' => $this->translatorHandler->translate(self::EMPTY_SELECT_VALUE),
            )
        ));

        $this->add(array(
            'name' => 'name',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
                'required' => 'required',
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => 'Name',
            ),
        ));

        $this->add(array(
            'name' => 'nameAr',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
                'required' => 'required',
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => 'Name in Arabic',
            ),
        ));

        $this->add(array(
            'name' => 'file',
            'type' => 'Zend\Form\Element\File',
            'attributes' => array(
                'accept' => 'application/vnd.openxmlformats-officedocument.presentationml.slideshow,application/vnd.openxmlformats-officedocument.presentationml.template,application/vnd.openxmlformats-officedocument.presentationml.presentation,application/vnd.ms-powerpoint,application/zip,application/octet-stream,application/pdf,pptx,potx,ppsx,thmx',
            ),
            'options' => array(
                'label' => '<p class="required">File</p> <p>Supported Extensions: zip,pdf,ppt,pptx</p>',
                'label_options' => array(
                    'disable_html_escape' => true,
                )
            ),
        ));

        $this->add(array(
            'name' => 'addMore',
            'type' => 'Zend\Form\Element',
            'attributes' => array(
                'class' => 'btn btn-primary',
                'value' => 'Add More',
                'type' => 'button',
                'onclick' => "addMoreResource('#resource_form_addMore', '#resource_form_name','#resource_form_nameAr','#resource_form_file', '', '', '', '', '')"
            )
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

        // Add buttons fieldset
        $buttonsFieldset = new ButtonsFieldset(/* $name = */ null, /* $options = */ array("create_button_only" => true));
        $this->add($buttonsFieldset);
    }

    /**
     * Bind an object to the form
     *
     * Ensures the object is populated with validated values.
     * 
     * @param  object $object
     * @param  int $flags ,default value is FormInterface::VALUES_NORMALIZED
     * @return mixed|void
     * @throws Exception\InvalidArgumentException
     */
    public function bind($object, $flags = FormInterface::VALUES_NORMALIZED)
    {
        parent::bind($object, $flags);
        // remove addMore button from edit form
        $this->remove("addMore");
    }

}
