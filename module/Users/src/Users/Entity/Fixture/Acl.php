<?php

namespace Users\Entity\Fixture;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Users\Entity\Acl as AclEntity;

/**
 * Acl Fixture
 * 
 * @package users
 * @subpackage entity
 */
class Acl extends AbstractFixture implements OrderedFixtureInterface,DependentFixtureInterface
{
    /**
     * Load acl fixture
     * 
     * @access public
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $acl = new AclEntity();

        $manager->persist($acl);
        $manager->flush();
        $this->addReference('acl', $acl);
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
        return 2; // number in which order to load fixtures
    }
}