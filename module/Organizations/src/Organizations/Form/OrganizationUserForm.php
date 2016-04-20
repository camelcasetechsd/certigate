<?php

namespace Organizations\Form;

use Utilities\Form\Form;
use Users\Entity\Role;
use Organizations\Entity\Organization;
use Doctrine\Common\Collections\Criteria;
use Utilities\Form\ButtonsFieldset;
use Organizations\Entity\OrganizationType;

/**
 * OrganizationUser Form
 * 
 * Handles OrganizationUser form setup
 * 
 * @property Utilities\Service\Query\Query $query
 * @property int $organizationType
 * 
 * @package organizations
 * @subpackage form
 */
class OrganizationUserForm extends Form
{

    /**
     *
     * @var Utilities\Service\Query\Query
     */
    public $query;

    /**
     *
     * @var int
     */
    protected $organizationType;

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
        $this->organizationType = $options['organizationType'];
        unset($options['organizationType']);
        parent::__construct($name, $options);

        $this->setAttribute('class', 'form form-horizontal');

        $types = array();
        $atcTypes = array(
            Role::PROCTOR_ROLE,
            Role::TEST_CENTER_ADMIN_ROLE,
        );
        $atpTypes = array(
            Role::TRAINING_MANAGER_ROLE,
        );

        if (in_array(OrganizationType::TYPE_ATC_TITLE, $this->organizationType) && in_array(OrganizationType::TYPE_ATP_TITLE, $this->organizationType)) {
            $types = array_merge($atpTypes, $atcTypes);
        }
        elseif (in_array(OrganizationType::TYPE_ATP_TITLE, $this->organizationType)) {
            $types = $atpTypes;
        }
        else if (in_array(OrganizationType::TYPE_ATC_TITLE, $this->organizationType)) {
            $types = $atcTypes;
        }

        $this->add(array(
            'name' => 'user',
            'type' => 'DoctrineModule\Form\Element\ObjectSelect',
            'attributes' => array(
                'class' => 'form-control chosen-select',
                'required' => 'required',
            ),
            'options' => array(
                'label' => 'User',
                'object_manager' => $this->query->entityManager,
                'target_class' => 'Users\Entity\User',
                'property' => 'fullName',
                'is_method' => true,
                'find_method' => array(
                    'name' => 'findBy',
                    'params' => array(
                        'criteria' => array()
                    )
                ),
                'display_empty_item' => true,
                'empty_item_label' => self::EMPTY_SELECT_VALUE,
            ),
        ));

        $criteria = Criteria::create();
        $expr = Criteria::expr();
        if (count($types) > 0) {
            $criteria->andWhere($expr->in("name", $types));
        }
        $this->add(array(
            'name' => 'role',
            'type' => 'DoctrineModule\Form\Element\ObjectSelect',
            'attributes' => array(
                'class' => 'form-control',
                'required' => 'required',
            ),
            'options' => array(
                'label' => 'Role',
                'object_manager' => $this->query->entityManager,
                'target_class' => 'Users\Entity\Role',
                'property' => 'name',
                'is_method' => false,
                'find_method' => array(
                    'name' => 'matching',
                    'params' => array(
                        'criteria' => $criteria
                    )
                ),
                'display_empty_item' => true,
                'empty_item_label' => self::EMPTY_SELECT_VALUE,
            ),
        ));

        $this->add(array(
            'name' => 'organization',
            'type' => 'Zend\Form\Element\Hidden',
        ));

        $this->add(array(
            'name' => 'id',
            'type' => 'Zend\Form\Element\Hidden',
        ));

        // Add buttons fieldset
        $buttonsFieldset = new ButtonsFieldset(/* $name = */ null, /* $options = */ array("create_button_only" => true));
        $this->add($buttonsFieldset);
    }

}
