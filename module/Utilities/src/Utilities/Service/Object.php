<?php

namespace Utilities\Service;

use Utilities\Service\Status;

/**
 * Object
 * 
 * Handles Object-related operations
 * 
 * 
 * @package utilities
 * @subpackage service
 */
class Object {

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
                if ($objectPropertyValue instanceof \DateTime) {
                    $object->$objectPropertyName = $objectPropertyValue->format("D, d M Y H:i");
                } elseif (is_object($objectPropertyValue) && $depthLevel != $maxDepthLevel){
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
                default:
                    break;
            }
        }
        return $objectProperties;
    }

}
