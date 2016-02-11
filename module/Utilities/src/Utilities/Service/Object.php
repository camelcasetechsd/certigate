<?php

namespace Utilities\Service;

use Utilities\Service\Status;
use LosI18n\Service\CountryService;
use LosI18n\Service\LanguageService;
use Utilities\Service\Time;
use Gedmo\Tool\Wrapper\AbstractWrapper;

/**
 * Object
 * 
 * Handles Object-related operations
 * 
 * @property array $countries
 * @property array $languages
 * @property Utilities\Service\Query\Query $query
 * 
 * @package utilities
 * @subpackage service
 */
class Object
{

    /**
     *
     * @var array 
     */
    public $countries;

    /**
     *
     * @var array 
     */
    public $languages;

    /**
     *
     * @var Utilities\Service\Query\Query 
     */
    public $query;

    /**
     * Set needed properties
     * 
     * 
     * @access public
     * @param CountryService $countriesService
     * @param LanguageService $languagesService
     * @param Utilities\Service\Query\Query $query
     */
    public function __construct(CountryService $countriesService, LanguageService $languagesService, $query)
    {
        $locale = "en";
        $this->countries = $countriesService->getAllCountries($locale);
        $this->languages = $languagesService->getAllLanguages($locale);
        $this->query = $query;
    }

    /**
     * prepare object for display
     * 
     * 
     * @access public
     * @param array $objectsArray
     * @param int $depthLevel ,default is 0
     * @param int $maxDepthLevel depth level including first object level ,default is 3
     * @return array objects prepared for display
     */
    public function prepareForDisplay($objectsArray, $depthLevel = 0, $maxDepthLevel = 3)
    {
        $depthLevel ++;
        foreach ($objectsArray as $object) {
            $objectProperties = $this->prepareForStatusDisplay($object);
            if ($depthLevel == 1) {
                $wrapped = AbstractWrapper::wrap($object, $this->query->entityManager);
                $meta = $wrapped->getMetadata();
            }
            foreach ($objectProperties as $objectPropertyName => $objectPropertyValue) {
                if (is_string($objectPropertyValue) && strlen($objectPropertyValue) <= 5) {
                    $textObjectPropertyName = $objectPropertyName . "Text";
                    if (array_key_exists($objectPropertyValue, $this->languages)) {
                        $object->$textObjectPropertyName = $this->languages[$objectPropertyValue];
                    }
                    elseif (strlen($objectPropertyValue) == 2 && array_key_exists($objectPropertyValue, $this->countries)) {
                        $object->$textObjectPropertyName = $this->countries[$objectPropertyValue];
                    }
                }
                elseif ($objectPropertyValue instanceof \DateTime) {
                    $formattedString = $objectPropertyValue->format("D, d M Y");
                    if ($formattedString == Time::UNIX_DATE_STRING) {
                        $formattedString = $objectPropertyValue->format("H:i");
                    }
                    $object->$objectPropertyName = $formattedString;
                }
                elseif (is_object($objectPropertyValue) && $depthLevel != $maxDepthLevel) {
                    $objectsPropertyValue = $this->prepareForDisplay(array($objectPropertyValue), $depthLevel, $maxDepthLevel);
                    $object->$objectPropertyName = reset($objectsPropertyValue);
                }
                elseif (is_array($objectPropertyValue) && array_key_exists("id", $objectPropertyValue) && isset($meta) && $meta->isSingleValuedAssociation($objectPropertyName)) {
                    $object->$objectPropertyName = $this->query->find($meta->getAssociationMapping($objectPropertyName)["targetEntity"], $objectPropertyValue["id"]);
                }
            }
        }
        return $objectsArray;
    }

    /**
     * prepare object status for display
     * 
     * 
     * @access public
     * @param mixed $object
     * @return array object properties array
     */
    public function prepareForStatusDisplay($object)
    {
        if (method_exists($object, /* $method_name = */ "getArrayCopy")) {
            $objectProperties = $object->getArrayCopy();
        }
        else {
            $objectProperties = get_object_vars($object);
        }
        if (array_key_exists("status", $objectProperties)) {
            switch ($object->status) {
                case Status::STATUS_ACTIVE:
                    $object->statusActive = TRUE;
                    $object->statusText = Status::STATUS_ACTIVE_TEXT;
                    break;
                case Status::STATUS_INACTIVE:
                    $object->statusIactive = TRUE;
                    $object->statusText = Status::STATUS_INACTIVE_TEXT;
                    break;
                case Status::STATUS_DELETED:
                    $object->statusDeleted = TRUE;
                    $object->statusText = Status::STATUS_DELETED_TEXT;
                    break;
                case Status::STATUS_NOT_APPROVED:
                    $object->statusNotApproved = TRUE;
                    $object->statusText = Status::STATUS_NOT_APPROVED_TEXT;
                    break;
                default:
                    break;
            }
        }
        return $objectProperties;
    }

    /**
     * get object id
     * 
     * @access public
     * @param mixed $unknownTypeObject
     * 
     * @return int id
     */
    public function getId($unknownTypeObject)
    {
        $id = null;
        if (is_numeric($unknownTypeObject)) {
            $id = $unknownTypeObject;
        }
        elseif (is_array($unknownTypeObject) && array_key_exists("id", $unknownTypeObject)) {
            $id = $unknownTypeObject["id"];
        }
        elseif (is_object($unknownTypeObject) && method_exists($unknownTypeObject, "getId")) {
            $id = $unknownTypeObject->getId();
        }
        return $id;
    }

}
