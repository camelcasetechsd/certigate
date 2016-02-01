<?php

namespace Notifications\Service;

use SlmQueue\Job\AbstractJob;
use SlmQueue\Queue\AbstractQueue;

/**
 * Notification
 * 
 * Handles Notification related business
 * 
 * 
 * @property SlmQueue\Job\AbstractJob $sendEmailJob
 * @property SlmQueue\Queue\AbstractQueue $queue
 * 
 * @package notifications
 * @subpackage service
 */
class Notification
{

    /**
     *
     * @var SlmQueue\Job\AbstractJob 
     */
    public $sendEmailJob;

    /**
     *
     * @var SlmQueue\Queue\AbstractQueue
     */
    public $queue;

    /**
     * Set needed properties
     * 
     * @access public
     * @param AbstractQueue $queue
     * @param AbstractJob $sendEmailJob
     */
    public function __construct(AbstractQueue $queue, AbstractJob $sendEmailJob)
    {
        $this->queue = $queue;
        $this->sendEmailJob = $sendEmailJob;
    }
    
    /**
     * Notify user via sending mail
     * 
     * @access public
     * @param array $mailArray
     * @throws \Exception Missing some required Mail option(s)
     */
    public function notify($mailArray){
        $requiredKeys = array('from', 'to', 'subject', 'body' );
        if(count(array_intersect_key(array_flip($requiredKeys), $mailArray)) !== count($requiredKeys)) {
            throw new \Exception("Missing some required Mail option(s)");
        }
        $this->sendEmailJob->setContent($mailArray);
        $this->queue->push($this->sendEmailJob);
    }

}
