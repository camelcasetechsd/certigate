<?php

namespace Notifications\Service\Job;

use SlmQueue\Job\AbstractJob;
use Zend\Mail\Message;
use Zend\Mail\Transport\TransportInterface;

/**
 * SendEmailJob
 * 
 * Handles SendEmail job related business
 * 
 * 
 * @property TransportInterface $transport
 * 
 * @package notifications
 * @subpackage job
 */
class SendEmailJob extends AbstractJob {

    /**
     *
     * @var TransportInterface 
     */
    public $transport;
    
    /**
     * Set needed properties
     * 
     * @access public
     * @param TransportInterface $transport
     */
    public function __construct(TransportInterface $transport)
    {
        $this->transport = $transport;
    }
    
    /**
     * Send email
     * 
     * @access public
     */
    public function execute()
    {
        $payload = $this->getContent();
        $from      = $payload['from'];
        $to      = $payload['to'];
        $subject = $payload['subject'];
        $body = $payload['body'];
        
        
        $message = new Message();
        $message->addTo($to)
                ->addFrom($from)
                ->setSubject($subject)
                ->setBody($body);

        if(array_key_exists('bcc', $payload)){
            $bcc = $payload['bcc'];
            $message->addBcc($bcc);
        }
        if(array_key_exists('cc', $payload)){
            $cc = $payload['cc'];
            $message->addCc($cc);
        }

        $this->transport->send($message);
    }
    

}
