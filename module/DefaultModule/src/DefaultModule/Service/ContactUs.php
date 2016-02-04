<?php

namespace DefaultModule\Service;

use Notifications\Service\MailTempates;
use System\Service\Cache\CacheHandler;
use System\Service\Settings;
use Notifications\Service\MailSubjects;

/**
 * ContactUs
 * 
 * Handle contact us related business
 * 
 * @property System\Service\Cache\CacheHandler $systemCacheHandler
 * @property Notifications\Service\Notification $notification
 * 
 * @package defaultModule
 * @subpackage service
 */
class ContactUs
{

    /**
     *
     * @var System\Service\Cache\CacheHandler
     */
    protected $systemCacheHandler;

    /**
     *
     * @var Notifications\Service\Notification
     */
    protected $notification;

    /**
     * Set needed properties
     * 
     * @access public
     * @param System\Service\Cache\CacheHandler $systemCacheHandler
     * @param Notifications\Service\Notification $notification
     */
    public function __construct($systemCacheHandler, $notification)
    {
        $this->systemCacheHandler = $systemCacheHandler;
        $this->notification = $notification;
    }

    /**
     * Submit contact us message
     * 
     * @access public
     * @param array $data
     * @param DefaultModule\Form\ContactUsForm $form
     * @return boolean true if message is sent successfully
     * @throws \Exception To email is not set
     */
    public function submitMessage($data, $form)
    {
        $submissionResult = array();

        $forceFlush = (APPLICATION_ENV == "production" ) ? false : true;
        $cachedSystemData = $this->systemCacheHandler->getCachedSystemData($forceFlush);
        $settings = $cachedSystemData[CacheHandler::SETTINGS_KEY];

        $toArray = array();
        if (array_key_exists(Settings::OPERATIONS_EMAIL, $settings) && array_key_exists(Settings::ADMIN_EMAIL, $settings)) {
            $toArray[] = $settings[Settings::OPERATIONS_EMAIL];
            $toArray[] = $settings[Settings::ADMIN_EMAIL];
        }
        if (count($toArray) == 0) {
            throw new \Exception("To email is not set");
        }
        $mailArray = array(
            'to' => $toArray,
            'from' => $data['email'],
            'templateName' => MailTempates::CONTACT_US_TEMPLATE,
            'templateParameters' => array(
                "data" => $data,
            ),
            'subject' => MailSubjects::CONTACT_US_SUBJECT,
        );
        $this->notification->notify($mailArray);

        $submissionResult[]['message'] = "Your message has been submitted successfully.";
        $submissionResult['messages'] = $submissionResult;
        $submissionResult['status'] = true;
        // clear form
        $form->setData(array('name' => '', 'subject' => '', 'message' => '', 'email' => ''));
        return $submissionResult;
    }

}
