<?php

namespace Organizations\Form;

use Utilities\Form\Form;
use Users\Entity\Role;
use Organizations\Entity\Organization;
use Doctrine\Common\Collections\Criteria;

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
        if ($this->organizationType === Organization::TYPE_ATC) {
            $types = $atcTypes;
        }
        elseif ($this->organizationType === Organization::TYPE_ATP) {
            $types = $atpTypes;
        }
        else{
            $types = array_merge($atpTypes, $atcTypes);
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

        $this->add(array(
            'name' => 'Create',
            'type' => 'Zend\Form\Element\Submit',
            'attributes' => array(
                'class' => 'btn btn-success',
                'value' => 'Add',
            )
        ));

        $this->add(array(
            'name' => 'reset',
            'type' => 'Zend\Form\Element',
            'attributes' => array(
                'class' => 'btn btn-danger resetButton',
                'value' => 'Reset',
                'type' => 'button',
            )
        ));
    }

}
