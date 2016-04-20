<?php

namespace Courses\Form;

use Utilities\Form\Form;
use Users\Entity\Role;
use Utilities\Form\ButtonsFieldset;
use Doctrine\Common\Collections\Criteria;
use Translation\Service\Locale\Locale;
use Zend\InputFilter\InputFilter;

/**
 * ExamBookProctor Form
 * 
 * Handles ExamBookProctor form setup
 * 
 * @property Utilities\Service\Query\Query $query
 * @property InputFilter $inputFilter validation constraints 
 * 
 * @package courses
 * @subpackage form
 */
class ExamBookProctorForm extends Form
{

    /**
     *
     * @var Utilities\Service\Query\Query
     */
    public $query;

    /**
     *
     * @var InputFilter validation constraints 
     */
    private $inputFilter;
    
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
        $organizationId = $options['organizationId'];
        $applicationLocale = $options['applicationLocale'];
        $translatorHandler = $options['translatorHandler'];
        $currentLocale = $applicationLocale->getCurrentLocale();
        parent::__construct($name, $options);

        $this->setAttribute('class', 'form form-horizontal');

        $role = $this->query->findOneBy(/* $entityName = */'Users\Entity\Role', /* $criteria = */ array(
            'name' => Role::PROCTOR_ROLE,
        ));
        $awayWord = $translatorHandler->translate("km away");
        $methodSuffix = (($currentLocale == Locale::LOCALE_AR_AR)? "Ar":"");
        $this->add(array(
            'name' => 'proctors',
            'type' => 'DoctrineModule\Form\Element\ObjectMultiCheckbox',
            'attributes' => array(
                'class' => 'form-control',
                'class' => 'mar',
            ),
            'options' => array(
                'label' => '',
                'object_manager' => $this->query->entityManager,
                'target_class' => 'Organizations\Entity\OrganizationUser',
                'label_generator' => function($targetEntity)use($methodSuffix, $awayWord) {
                    $fullNameMethodName = "getFullName".$methodSuffix;
                    $user = $targetEntity->getUser();
                    return $user->$fullNameMethodName()." - ".$user->getCity()." <span class='".$targetEntity->getDistanceStyleClass()."'>[ ~ ".$targetEntity->getDistanceSort()." $awayWord]</span>";
                },
                'find_method' => array(
                    'name' => 'findBy',
                    'params' => array(
                        'criteria' => array(
                            "role" => $role,
                            "organization" => $organizationId
                        ),
                        'orderBy' => array(
                            "distanceSort" => Criteria::ASC
                        ),
                        'limit' => 10,
                    )
                ),
                'label_options' => array(
                    'disable_html_escape' => true,
                ),
            ),
        ));

        $this->add(array(
            'name' => 'id',
            'type' => 'Zend\Form\Element\Hidden',
        ));

        // Add buttons fieldset
        $buttonsFieldset = new ButtonsFieldset(/*$name =*/ null, /*$options =*/ array("create_button_only" => true));
        $this->add($buttonsFieldset);
        
        $this->setInputFilter($this->getInputFilter());
    }

    /**
     * set validation constraints
     * 
     * @uses InputFilter
     * 
     * @access public
     * @return InputFilter validation constraints
     */
    public function getInputFilter() {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();
            
            $inputFilter->add(array(
                'name' => 'proctors',
                'required' => true,
            ));

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }
}
