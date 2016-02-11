<?php

namespace Versioning\Listener;

use Gedmo\Loggable\LoggableListener as OriginalLoggableListener;
use Doctrine\Common\EventArgs;
use Gedmo\Tool\Wrapper\AbstractWrapper;

/**
 * Loggable listener
 * 
 * Handles Entity changes logging related business
 *
 * @property Gedmo\Mapping\Event\AdapterInterface $eventAdapter
 * 
 * @package versioning
 * @subpackage listener
 */
class LoggableListener extends OriginalLoggableListener
{

    /**
     *
     * @var Gedmo\Mapping\Event\AdapterInterface 
     */
    protected $eventAdapter;

    /**
     * Handle any custom LogEntry functionality that needs to be performed
     * before persisting it
     *
     * @access protected
     * @param object $logEntry The LogEntry being persisted
     * @param object $object   The object being Logged
     */
    protected function prePersistLogEntry($logEntry, $object)
    {
        $objectManager = $this->eventAdapter->getObjectManager();
        $wrapped = AbstractWrapper::wrap($object, $objectManager);
        $meta = $wrapped->getMetadata();
        $config = $this->getConfiguration($objectManager, $meta->name);
        $objectData = $object->getArrayCopy();
        $loggedData = $logEntry->getData();
        $unsetObjectData = array();
        foreach ($objectData as $fieldName => $fieldValue) {
            if (empty($config['versioned']) || !in_array($fieldName, $config['versioned'])) {
                continue;
            }
            if (!array_key_exists($fieldName, $loggedData) && in_array($fieldName, $config['versioned'])) {
                $unsetObjectData[$fieldName] = $fieldValue;
            }
        }

        $processedUnsetObjectData = $this->processObjectUnchangedData($unsetObjectData, $object);
        $logEntry->setData(array_merge($processedUnsetObjectData, $loggedData));
    }

    /**
     * Process an object's unchanged data
     *
     * @access protected
     * @param array $unchangedData
     * @param object $object
     *
     * @return array unchanged data
     */
    protected function processObjectUnchangedData($unchangedData, $object)
    {
        $objectManager = $this->eventAdapter->getObjectManager();
        $wrapped = AbstractWrapper::wrap($object, $objectManager);
        $meta = $wrapped->getMetadata();
        $unchangedValues = array();

        foreach ($unchangedData as $field => $value) {
            if ($meta->isSingleValuedAssociation($field) && $value) {
                $wrappedAssoc = AbstractWrapper::wrap($value, $objectManager);
                $value = $wrappedAssoc->getIdentifier(false);
            }
            $unchangedValues[$field] = $value;
        }

        return $unchangedValues;
    }

    /**
     * Looks for loggable objects being inserted or updated
     * for further processing
     *
     * @access public
     * @param EventArgs $eventArgs
     *
     * @return void
     */
    public function onFlush(EventArgs $eventArgs)
    {
        $this->eventAdapter = $this->getEventAdapter($eventArgs);
        parent::onFlush($eventArgs);
    }

}
