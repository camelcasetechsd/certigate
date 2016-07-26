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
 * @property CountryService $countriesService
 * @property LanguageService $languagesService
 * @property array $countries
 * @property array $languages
 * @property Utilities\Service\Query\Query $query
 * @property array $statusConstants
 * @property array $ignoredProperties
 * 
 * @package utilities
 * @subpackage service
 */
class Object
{

    const DATE_DISPLAY_FORMAT = "D, d M Y";
    const TIME_DISPLAY_FORMAT = "H:i";

    /**
     *
     * @var CountryService 
     */
    public $countriesService;

    /**
     *
     * @var LanguageService 
     */
    public $languagesService;

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
     *
     * @var array
     */
    public $statusConstants;

    /**
     *
     * @var array
     */
    public $ignoredProperties = array(
        "inputFilter"
    );

    /**
     * Set needed properties
     * 
     * 
     * @access public
     * @param CountryService $countriesService
     * @param LanguageService $languagesService
     * @param Utilities\Service\Query\Query $query
     * @param Translation\Service\Locale\Locale $applicationLocale
     */
    public function __construct(CountryService $countriesService, LanguageService $languagesService, $query, $applicationLocale)
    {
        $this->countriesService = $countriesService;
        $this->languagesService = $languagesService;
        $locale = $applicationLocale->getCurrentLanguageCode();
        $this->setLocale($locale);
        $this->query = $query;
        $statusReflection = new \ReflectionClass('Utilities\Service\Status');
        $this->statusConstants = $statusReflection->getConstants();
    }

    /**
     * prepare object for save
     * 
     * 
     * @access public
     * @param array $objectsArray
     * @return array objects prepared for save
     */
    public function prepareForSave($objectsArray)
    {
        foreach ($objectsArray as &$object) {
            $objectProperties = $this->getObjectProperties($object);
            foreach ($objectProperties as $objectPropertyName => $objectPropertyValue) {
                if (is_string($objectPropertyValue) && !empty($objectPropertyValue)) {
                    $dateTime = \DateTime::createFromFormat(self::DATE_DISPLAY_FORMAT, $objectPropertyValue);
                    if ($dateTime !== FALSE) {
                        $object->$objectPropertyName = $dateTime;
                    }
                }
            }
        }
        return $objectsArray;
    }

    /**
     * prepare object for display
     * 
     * 
     * @access public
     * @param array $objectsArray
     * @param mixed $sampleObject object used to get expected properties when $objectsArray is array of arrays not array of objects ,default is null
     * @param int $depthLevel ,default is 0
     * @param int $maxDepthLevel depth level including first object level ,default is 3
     * @return array objects prepared for display
     */
    public function prepareForDisplay($objectsArray, $sampleObject = null, $depthLevel = 0, $maxDepthLevel = 3)
    {
        $depthLevel ++;
        foreach ($objectsArray as &$object) {
            $notObject = false;
            // support array of arrays instead of array of objects
            if (is_array($object)) {
                $object = (object) $object;
                $notObject = true;
            }
            $objectProperties = $this->prepareForStatusDisplay($object);
            if (($notObject === false || ($notObject === true && !is_null($sampleObject))) && $depthLevel == 1) {
                if (is_null($sampleObject)) {
                    $sampleObjectForWrapper = $object;
                }
                else {
                    $sampleObjectForWrapper = $sampleObject;
                }
                $wrapped = AbstractWrapper::wrap($sampleObjectForWrapper, $this->query->entityManager);
                $meta = $wrapped->getMetadata();
            }
            foreach ($objectProperties as $objectPropertyName => $objectPropertyValue) {
                if (in_array($objectPropertyName, $this->ignoredProperties)) {
                    // skip ignored properties
                    continue;
                }
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
                    $formattedString = $objectPropertyValue->format(self::DATE_DISPLAY_FORMAT);
                    if ($formattedString == Time::UNIX_DATE_STRING) {
                        $formattedString = $objectPropertyValue->format("H:i");
                    }
                    $object->$objectPropertyName = $formattedString;
                }
                elseif (is_object($objectPropertyValue) && $depthLevel != $maxDepthLevel) {
                    $objectsPropertyValue = $this->prepareForDisplay(array($objectPropertyValue), $sampleObject, $depthLevel, $maxDepthLevel);
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
        $objectProperties = $this->getObjectProperties($object);

        if (array_key_exists("status", $objectProperties)) {
            $statusKey = array_search((string)$object->status, $this->statusConstants);
            if ($statusKey !== false) {
                $statusExplosionArray = explode('_', $statusKey);
                if (in_array('TEXT', $statusExplosionArray)) {
                    $object->statusText = $this->statusConstants[$statusKey];
                }
                else {
                    $object->statusText = $this->statusConstants[$statusKey . "_TEXT"];
                }
            }

            $object->statusActive = false;
            $object->statusIsactive = false;
            $object->statusDeleted = false;
            $object->statusNotApproved = false;
            $object->statusStateSaved = false;
            switch ($object->status) {
                case Status::STATUS_ACTIVE:
                    $object->statusActive = TRUE;
                    break;
                case Status::STATUS_INACTIVE:
                    $object->statusIsactive = TRUE;
                    break;
                case Status::STATUS_DELETED:
                    $object->statusDeleted = TRUE;
                    break;
                case Status::STATUS_NOT_APPROVED:
                    $object->statusNotApproved = TRUE;
                    break;
                case Status::STATUS_STATE_SAVED:
                    $object->statusStateSaved = TRUE;
                    break;
                default:
                    break;
            }
        }
        return $objectProperties;
    }

    /**
     * Get object properties
     * 
     * @access public
     * @param mixed $object
     * 
     * @return array object properties
     */
    public function getObjectProperties($object)
    {
        if (method_exists($object, /* $method_name = */ "getArrayCopy")) {
            $objectProperties = $object->getArrayCopy();
        }
        else {
            $objectProperties = get_object_vars($object);
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

    /**
     * set locale
     * 
     * @access private
     * @param string $locale
     */
    private function setLocale($locale)
    {
        $this->countries = $this->countriesService->getAllCountries($locale);
        $this->languages = $this->languagesService->getAllLanguages($locale);
    }

}
