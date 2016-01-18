<?php

namespace Utilities\Service;

use Utilities\Service\Status;
use LosI18n\Service\CountryService;
use LosI18n\Service\LanguageService;
use Utilities\Service\Time;

/**
 * Object
 * 
 * Handles Object-related operations
 * 
 * @property array $countries
 * @property array $languages
 * 
 * @package utilities
 * @subpackage service
 */
class Object {

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
     * Set needed properties
     * 
     * 
     * @access public
     * @param CountryService $countriesService
     * @param LanguageService $languagesService
     */
    public function __construct(CountryService $countriesService, LanguageService $languagesService) {
        $locale = "en";
        $this->countries = $countriesService->getAllCountries($locale);
        $this->languages = $languagesService->getAllLanguages($locale);
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
    public function prepareForDisplay(array $objectsArray, $depthLevel = 0, $maxDepthLevel = 3) {
        $depthLevel ++;
        foreach ($objectsArray as $object) {
            $objectProperties = $this->prepareForStatusDisplay($object);
            foreach ($objectProperties as $objectPropertyName => $objectPropertyValue) {
                if(is_string($objectPropertyValue) && strlen($objectPropertyValue) <= 5 ) {
                    $textObjectPropertyName = $objectPropertyName."Text";
                    if(array_key_exists($objectPropertyValue, $this->languages)){
                        $object->$textObjectPropertyName = $this->languages[$objectPropertyValue];
                    }elseif(strlen($objectPropertyValue) == 2 && array_key_exists($objectPropertyValue, $this->countries)){
                        $object->$textObjectPropertyName = $this->countries[$objectPropertyValue];
                    }
                    
                } elseif ($objectPropertyValue instanceof \DateTime) {
                    $formattedString = $objectPropertyValue->format("D, d M Y");
                    if($formattedString == Time::UNIX_DATE_STRING){
                        $formattedString = $objectPropertyValue->format("H:i");
                    }
                    $object->$objectPropertyName = $formattedString;
                } elseif (is_object($objectPropertyValue) && $depthLevel != $maxDepthLevel) {
                    $objectsPropertyValue = $this->prepareForDisplay(array($objectPropertyValue), $depthLevel, $maxDepthLevel);
                    $object->$objectPropertyName = reset($objectsPropertyValue);
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
    public function prepareForStatusDisplay($object) {
        if (method_exists($object, /* $method_name = */ "getArrayCopy")) {
            $objectProperties = $object->getArrayCopy();
        } else {
            $objectProperties = get_object_vars($object);
        }
        if (array_key_exists("status", $objectProperties)) {
            switch ($object->status) {
                case Status::STATUS_ACTIVE:
                    $object->statusText = Status::STATUS_ACTIVE_TEXT;
                    break;
                case Status::STATUS_INACTIVE:
                    $object->statusText = Status::STATUS_INACTIVE_TEXT;
                    break;
                case Status::STATUS_DELETED:
                    $object->statusText = Status::STATUS_DELETED_TEXT;
                    break;
                case Status::STATUS_NOT_APPROVED:
                    $object->statusText = Status::STATUS_NOT_APPROVED_TEXT;
                    break;
                default:
                    break;
            }
        }
        return $objectProperties;
    }

}
