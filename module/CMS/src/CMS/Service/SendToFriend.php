<?php

namespace CMS\Service;

use Notifications\Service\MailTemplates;
use System\Service\Cache\CacheHandler;
use System\Service\Settings;
use Utilities\Service\MessageTypes;

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
class SendToFriend
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

        $fromArray = array();
        if (array_key_exists(Settings::OPERATIONS_EMAIL, $settings)) {
            $fromArray[] = $settings[Settings::OPERATIONS_EMAIL];
        }
        if (count($fromArray) == 0) {
            throw new \Exception("To email is not set");
        }
        $mailArray = array(
            'from' => $fromArray,
            'to' => $data['email'],
            'templateName' => MailTemplates::SEND_TO_FRIEND_TEMPLATE,
            'templateParameters' => array(
                "data" => $data,
            ),
            'subject' => $data["subject"],
        );
        $this->notification->notify($mailArray);

        $submissionResult[]['message'] = "Your message has been submitted successfully.";
        $submissionResult['messages'] = $submissionResult;
        $submissionResult['type'] = MessageTypes::SUCCESS;
        // clear form
        $form->setData(array('name' => '', 'subject' => '', 'message' => '', 'email' => ''));
        return $submissionResult;
    }

}
