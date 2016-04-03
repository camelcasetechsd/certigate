<?php

namespace Notifications\Service\Worker;

use SlmQueueDoctrine\Worker\DoctrineWorker as OriginalDoctrineWorker;
use SlmQueue\Queue\QueueInterface;
use Zend\Stdlib\ArrayUtils;
use SlmQueue\Worker\WorkerEvent;

/**
 * DoctrineWorker
 * 
 * Handles Doctrine worker related business
 * 
 * 
 * @package notifications
 * @subpackage worker
 */
class DoctrineWorker extends OriginalDoctrineWorker
{

    /**
     * {@inheritDoc}
     */
    public function processQueue(QueueInterface $queue, array $options = [])
    {
        $startTime = $currentTime = microtime(/* $get_as_float =*/ true);
        $eventManager = $this->eventManager;
        $workerEvent = new WorkerEvent($this, $queue);

        $workerEvent->setOptions($options);

        $eventManager->trigger(WorkerEvent::EVENT_BOOTSTRAP, $workerEvent);

        $timeout = 0;
        if (array_key_exists("timeout", $options)) {
            // use only a percentage of timeout to compensate time consumed in other parts of the script
            $timeout = (int)$options["timeout"]  * 60 * 0.99;
        }
        while (!$workerEvent->shouldExitWorkerLoop() && ($currentTime - $startTime) <= $timeout) {
            $eventManager->trigger(WorkerEvent::EVENT_PROCESS_QUEUE, $workerEvent);
            $currentTime = microtime(/* $get_as_float =*/ true);
        }

        $eventManager->trigger(WorkerEvent::EVENT_FINISH, $workerEvent);

        $queueState = $eventManager->trigger(WorkerEvent::EVENT_PROCESS_STATE, $workerEvent);

        $queueState = array_filter(ArrayUtils::iteratorToArray($queueState));

        return $queueState;
    }

}
