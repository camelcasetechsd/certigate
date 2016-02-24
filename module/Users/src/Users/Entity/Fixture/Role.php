<?php

namespace Users\Entity\Fixture;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Users\Entity\Role as RoleEntity;

/**
 * Role Fixture
 * 
 * @package users
 * @subpackage entity
 */
class Role extends AbstractFixture implements OrderedFixtureInterface,DependentFixtureInterface
{
    /**
     * Load role fixture
     * 
     * @access public
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $proctorRole = new RoleEntity();
        $proctorRole->setName(RoleEntity::PROCTOR_ROLE);
        $manager->persist($proctorRole);
        
        $studentRole = new RoleEntity();
        $studentRole->setName(RoleEntity::STUDENT_ROLE);
        $manager->persist($studentRole);
        
        $testCenterAdminRole = new RoleEntity();
        $testCenterAdminRole->setName(RoleEntity::TEST_CENTER_ADMIN_ROLE);
        $manager->persist($testCenterAdminRole);
        
        $trainingManagerRole = new RoleEntity();
        $trainingManagerRole->setName(RoleEntity::TRAINING_MANAGER_ROLE);
        $manager->persist($trainingManagerRole);
        
        $userRole = new RoleEntity();
        $userRole->setName(RoleEntity::USER_ROLE);
        $manager->persist($userRole);
        
        $adminRole = new RoleEntity();
        $adminRole->setName(RoleEntity::ADMIN_ROLE);
        $manager->persist($adminRole);
        
        $manager->flush();
        $this->addReference('role', $proctorRole);
    }
    
    /**
     * Get Fixture dependencies
     * 
     * @access public
     * @return array
     */
    public function getDependencies()
    {
        return array(
            ); // fixture classes fixture is dependent on
    }
    
    /**
     * Get Fixture order
     * 
     * @access public
     * @return int
     */
    public function getOrder()
    {
        return 1; // number in which order to load fixtures
    }
}