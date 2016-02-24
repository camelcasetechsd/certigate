<?php

namespace Users\Entity\Fixture;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Users\Entity\User as UserEntity;

/**
 * User Fixture
 * 
 * @package users
 * @subpackage entity
 */
class User extends AbstractFixture implements OrderedFixtureInterface,DependentFixtureInterface
{
    /**
     * Load user fixture
     * 
     * @access public
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $user = new UserEntity();
        $user->setUsername('jwage');
        $user->setPassword('test');

        $manager->persist($user);
        $manager->flush();
        $this->addReference('user', $user);
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
            'Users\Entity\Fixture\Role',
            'Users\Entity\Fixture\Acl',
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
        return 3; // number in which order to load fixtures
    }
}