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
     * @return array objects prepared for display
     */
    public function prepareForDisplay(array $objectsArray) {
        foreach ($objectsArray as $object) {
            if (method_exists($object, /* $method_name = */ "getArrayCopy")) {
                $objectProperties = $object->getArrayCopy();
            } else {
                $objectProperties = get_object_vars($object);
            }
            if (array_key_exists("status", $objectProperties)) {
                switch ($object->status) {
                    case Status::STATUS_ACTIVE:
                        $object->status = Status::STATUS_ACTIVE_TEXT;
                        break;
                    case Status::STATUS_INACTIVE:
                        $object->status = Status::STATUS_INACTIVE_TEXT;
                        break;
                    case Status::STATUS_DELETED:
                        $object->status = Status::STATUS_DELETED_TEXT;
                        break;
                    default:
                        break;
                }
            }
            foreach ($objectProperties as $objectPropertyName => $objectPropertyValue) {
                if ($objectPropertyValue instanceof \DateTime) {
                    $object->$objectPropertyName = $objectPropertyValue->format("D, d M Y H:i");
                }
            }
        }
        return $objectsArray;
    }

}
