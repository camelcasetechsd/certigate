<?php

namespace Versioning\Listener;

use Gedmo\Loggable\LoggableListener as OriginalLoggableListener;
use Doctrine\Common\EventArgs;
use Gedmo\Tool\Wrapper\AbstractWrapper;
use Utilities\Service\Status;
use Gedmo\Loggable\Mapping\Event\LoggableAdapter;
use Versioning\Entity\LogEntry;

/**
 * Loggable listener
 * 
 * Handles Entity changes logging related business
 *
 * @property int $userId
 * @property Gedmo\Mapping\Event\AdapterInterface $eventAdapter
 * 
 * @package versioning
 * @subpackage listener
 */
class LoggableListener extends OriginalLoggableListener
{

    /**
     *
     * @var int 
     */
    protected $userId;

    /**
     *
     * @var Gedmo\Mapping\Event\AdapterInterface 
     */
    protected $eventAdapter;

    /**
     * Set userId
     *
     * @access public
     * @param string $userId
     */
    public function setUserId($userId)
    {
        $this->userId = (int) $userId;
    }

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
        $newData = array_merge($processedUnsetObjectData, $loggedData);
        $logEntry->setData($newData);
        if (array_key_exists("status", $newData)) {
            $logEntry->setObjectStatus($newData["status"]);
        }
        $logEntry->setUserId($this->userId);
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
        $entityManager = $eventArgs->getEntityManager();
        $unitOfWork = $entityManager->getUnitOfWork();

        foreach ($this->eventAdapter->getScheduledObjectInsertions($unitOfWork) as $object) {
            $this->createLogEntry(self::ACTION_CREATE, $object, $this->eventAdapter);
        }
        foreach ($this->eventAdapter->getScheduledObjectDeletions($unitOfWork) as $object) {
            $this->createLogEntry(self::ACTION_REMOVE, $object, $this->eventAdapter);
        }

        foreach ($this->eventAdapter->getScheduledObjectUpdates($unitOfWork) as $entity) {
            $entityChangeSet = $unitOfWork->getEntityChangeSet($entity);
            // on approval, delete old versions
            if (array_key_exists("status", $entityChangeSet) && reset($entityChangeSet["status"]) == Status::STATUS_NOT_APPROVED && end($entityChangeSet["status"]) != Status::STATUS_NOT_APPROVED) {
                $objectClass = get_class($entity);
                $logClass = $this->getLogEntryClass($this->eventAdapter, $objectClass);
                $parameters = array(
                    'objectId' => $entity->getId(),
                    'objectClass' => $objectClass,
                );
                $queryBuilder = $entityManager->createQueryBuilder();
                $queryBuilder->delete($logClass, "log")
                        ->andWhere($queryBuilder->expr()->eq('log.objectId', ":objectId"))
                        ->andWhere($queryBuilder->expr()->eq('log.objectClass', ":objectClass"))
                        ->setParameters($parameters)
                        ->getQuery()->execute();
            }
            else {
                // on editing by non-admin where entity is supposed to be not approved, do not update entity
                if (array_key_exists("status", $entityChangeSet) && reset($entityChangeSet["status"]) != Status::STATUS_NOT_APPROVED && end($entityChangeSet["status"]) == Status::STATUS_NOT_APPROVED) {
                    $oid = spl_object_hash($entity);
                    $unitOfWork->clearEntityChangeSet($oid);
                }
                $this->createLogEntry(self::ACTION_UPDATE, $entity, $this->eventAdapter);
            }
        }
    }

    /**
     * Get the LogEntry class
     *
     * @param LoggableAdapter $eventAdapter
     * @param string $class
     *
     * @return string
     */
    protected function getLogEntryClass(LoggableAdapter $eventAdapter, $class)
    {
        if (isset(self::$configurations[$this->name][$class]['logEntryClass'])) {
            $class = self::$configurations[$this->name][$class]['logEntryClass'];
        }
        else {
            $class = $this->getDefaultLogEntryClass();
        }
        return $class;
    }

    /**
     * Get the default LogEntry class
     *
     * @return string
     */
    protected function getDefaultLogEntryClass()
    {
        return get_class(new LogEntry());
    }

}
