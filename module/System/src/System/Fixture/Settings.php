<?php

namespace System\Fixture;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use System\Service\Settings as SettingsService;
use System\Entity\Setting as SettingEntity;

/**
 * Settings Fixture
 * 
 * @package system
 * @subpackage fixture
 */
class Settings extends AbstractFixture implements OrderedFixtureInterface
{

    /**
     * Load setting fixture
     * 
     * @access public
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create();
        $settings = array(
            array(
                'name' => SettingsService::ADMIN_EMAIL,
                'value' => $faker->freeEmail
            ),
            array(
                'name' => SettingsService::OPERATIONS_EMAIL,
                'value' => $faker->freeEmail
            ),
            array(
                'name' => SettingsService::SYSTEM_EMAIL,
                'value' => $faker->freeEmail
            ),
        );

        foreach ($settings as $settingData) {
            $setting = new SettingEntity();

            $setting->setName($settingData["name"])
                    ->setValue($settingData["value"])
                    ;

            $manager->persist($setting);
        }
        $manager->flush();
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
