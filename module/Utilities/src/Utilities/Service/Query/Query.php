<?php

namespace Utilities\Service\Query;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Query
 * 
 * Handles database queries related business
 * Wrapping commonly used database queries
 * 
 * 
 * 
 * @property ObjectManager $entityManager
 * @property Doctrine\Common\Persistence\ObjectRepository $entityRepository
 * @property string $entityName
 * 
 * @package utilities
 * @subpackage query
 */
class Query
{

    /**
     *
     * @var ObjectManager 
     */
    public $entityManager;

    /**
     *
     * @var Doctrine\Common\Persistence\ObjectRepository 
     */
    public $entityRepository;

    /**
     *
     * @var string 
     */
    public $entityName;

    /**
     * Set needed properties
     * 
     * 
     * @access public
     * @param ObjectManager $entityManager
     */
    public function __construct(ObjectManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Set entity that is to-be-queried
     * 
     * 
     * @access public
     * @param string $entityName
     * @return \Utilities\Service\Query\Query
     */
    public function setEntity($entityName)
    {
        if (!empty($entityName)) {
            $this->entityName = $entityName;
            $this->entityRepository = $this->entityManager->getRepository($entityName);
        }
        return $this;
    }

    /**
     * Finds an entity by its primary key / identifier.
     * 
     * 
     * @access public
     * @param string $entityName
     * @param mixed $id The identifier.
     *
     * @return mixed object|null The entity instance or NULL if the entity can not be found.
     */
    public function find($entityName, $id)
    {
        return $this->setEntity($entityName)->entityRepository->find($id);
    }

    /**
     * Finds all entities in the repository.
     * 
     * 
     * @access public
     * @param string $entityName
     * @return array The entities.
     */
    public function findAll($entityName)
    {
        return $this->setEntity($entityName)->entityRepository->findAll();
    }

    /**
     * Finds entities by a set of criteria.
     * 
     * 
     * @access public
     * @param string $entityName
     * @param array  $criteria
     * @param array  $orderBy ,default is null
     * @param int    $limit ,default is null
     * @param int    $offset ,default is null
     *
     * @return array The objects.
     */
    public function findBy($entityName, array $criteria, array $orderBy = null, $limit = null, $offset = null)
    {
        return $this->setEntity($entityName)->entityRepository->findBy($criteria, $orderBy, $limit, $offset);
    }

    /**
     * Finds a single entity by a set of criteria.
     * 
     * 
     * @access public
     * @param string $entityName
     * @param array $criteria
     * @param array $orderBy ,default is null
     *
     * @return mixed object|null The entity instance or NULL if the entity can not be found.
     */
    public function findOneBy($entityName, array $criteria, array $orderBy = null)
    {
        return $this->setEntity($entityName)->entityRepository->findOneBy($criteria, $orderBy);
    }

    /**
     * Filter entities by a set of criteria.
     * Only count of entities can be retrieved
     * 
     * 
     * @access public
     * @uses Criteria
     * 
     * @param string $entityName
     * @param mixed $criteria Criteria instance ,default is bool false
     * @param bool $countFlag ,default is bool false
     * @return mixed array of results or just int if count is required
     */
    public function filter($entityName, $criteria = false, $countFlag = false)
    {
        if (!$criteria instanceof Criteria) {
            $criteria = new Criteria();
        }
        $return = $this->setEntity($entityName)->entityRepository->matching($criteria)->toArray();
        if ($countFlag === true) {
            $return = count($return);
        }
        return $return;
    }

    /**
     * Save entity in database
     * If entity's association hold id not actual object,
     * Then find that object to set the corresponding property with it
     * If data is passed to method, then if exchangeArray method exists in entity,
     * It will be called with the passed data as a parameter
     * 
     * 
     * @access public
     * @param mixed $entity entity object to be persisted
     * @param array $data ,default is empty array
     * @param bool $flushAll ,default is false
     * @param bool $noFlush ,default is false
     */
    public function save($entity, $data = array(), $flushAll = false, $noFlush = false)
    {
        // if association hold id not actual object, 
        // then find that object to set the corresponding property with it
        $classMetadata = $this->entityManager->getClassMetadata($this->entityName);
        $associationNames = $classMetadata->getAssociationNames();
        foreach ($associationNames as $associationName) {
            if (isset($data[$associationName])) {
                $currentValue = $data[$associationName];
            }
            else {
                $currentValue = $entity->$associationName;
            }
            if (!is_object($currentValue)) {
                $currentValueArray = $currentValue;
                $currentValueArrayFlag = true;
                // handle case where current value should be object or id, but instead an array with key id holding id value is received
                if (is_array($currentValue) && array_key_exists("id", $currentValue) && count($currentValue) == 1) {
                    $currentValue = $currentValue["id"];
                }
                if (is_numeric($currentValue) ) {
                    $currentValueArray = array($currentValue);
                    $currentValueArrayFlag = false;
                }
                if (is_array($currentValueArray)) {
                    $processedValueArrayCollection = null;
                    $targetClass = $classMetadata->getAssociationTargetClass($associationName);
                    foreach ($currentValueArray as $currentValue) {
                        if (is_numeric($currentValue)) {
                            $processedValue = $this->find($targetClass, $currentValue);
                        }
                        if ($currentValueArrayFlag === true) {
                            if (is_null($processedValueArrayCollection)) {
                                $processedValueArrayCollection = new ArrayCollection();
                            }
                            $processedValueArrayCollection->add($processedValue);
                        }
                        else {
                            if (isset($data[$associationName])) {
                                $data[$associationName] = $processedValue;
                            }
                            else {
                                $entity->$associationName = $processedValue;
                            }
                        }
                    }
                    if ($currentValueArrayFlag === true) {
                        if (isset($data[$associationName])) {
                            $data[$associationName] = $processedValueArrayCollection;
                        }
                        else {
                            $entity->$associationName = $processedValueArrayCollection;
                        }
                    }
                }
            }
        }

        if (!empty($data) && method_exists($entity, 'exchangeArray')) {
            $entity->exchangeArray($data);
        }
        $this->entityManager->persist($entity);
        if ($noFlush === false) {
            if ($flushAll === true) {
                $this->entityManager->flush();
            }
            else {
                $this->entityManager->flush($entity);
            }
        }
    }

    /**
     * Remove entity from database
     * 
     * 
     * @access public
     * @param mixed $entity entity object to be removed
     * @param bool $noFlush ,default is false
     */
    public function remove($entity, $noFlush = false)
    {
        $this->entityManager->remove($entity);
        if ($noFlush === false) {
        $this->entityManager->flush($entity);
        }
    }

    function checkExistance($entityName, $targetColumn, $value)
    {
        $Entity = $this->findOneBy($entityName, array(
            $targetColumn => $value
        ));

        if ($Entity == Null) {
            return false;
        }
        return True;
    }

}
