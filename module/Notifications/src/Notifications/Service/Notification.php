<?php

namespace Notifications\Service;

use SlmQueue\Job\AbstractJob;
use SlmQueue\Queue\AbstractQueue;
use Zend\View\Model\ViewModel;
use Zend\View\Renderer\RendererInterface;
use Zend\Mime\Message as MimeMessage;
use Zend\Mime\Part as MimePart;
/**
 * Notification
 * 
 * Handles Notification related business
 * 
 * 
 * @property SlmQueue\Job\AbstractJob $sendEmailJob
 * @property SlmQueue\Queue\AbstractQueue $queue
 * @property Zend\View\Renderer\RendererInterface $viewRenderer
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
     *
     * @var Zend\View\Renderer\RendererInterface
     */
    public $viewRenderer;

    /**
     * Set needed properties
     * 
     * @access public
     * @param AbstractQueue $queue
     * @param AbstractJob $sendEmailJob
     * @param RendererInterface $viewRenderer
     */
    public function __construct(AbstractQueue $queue, AbstractJob $sendEmailJob, RendererInterface $viewRenderer)
    {
        $this->queue = $queue;
        $this->sendEmailJob = $sendEmailJob;
        $this->viewRenderer = $viewRenderer;
    }

    /**
     * Notify user via sending mail
     * 
     * @access public
     * @param array $mailArray
     * @throws \Exception Missing some required Mail option(s)
     */
    public function notify($mailArray)
    {
        $requiredKeys = array('from', 'to', 'subject', 'templateName', 'templateParameters');
        if (count(array_intersect_key(array_flip($requiredKeys), $mailArray)) !== count($requiredKeys)) {
            throw new \Exception("Missing some required Mail option(s)");
        }
        $mailViewModel = new ViewModel($mailArray['templateParameters']);
        $mailViewModel->setTemplate("notifications/mail/" . $mailArray['templateName']);

        $layout = new ViewModel();
        $layout->setTemplate("notifications/mail/layout");
        $layout->setVariable("emailBody", $this->viewRenderer->render($mailViewModel));

        $htmlMarkup = $this->viewRenderer->render($layout);
        $html = new MimePart($htmlMarkup);
        $html->type = "text/html";

        $body = new MimeMessage();
        $body->setParts(array($html));
        $mailArray["body"] = $body;
        $this->sendEmailJob->setContent($mailArray);
        $this->queue->push($this->sendEmailJob);
    }

}
