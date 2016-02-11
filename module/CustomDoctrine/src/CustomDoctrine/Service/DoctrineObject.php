<?php

namespace CustomDoctrine\Service;

use DoctrineModule\Stdlib\Hydrator\DoctrineObject as OriginalDoctrineObject;
use Utilities\Service\Time;

/**
 * DoctrineObjectHydrator
 * 
 * Extract/hydrate objects in Doctrine, by handling most associations types
 * 
 * 
 * 
 * @package customDoctrine
 * @subpackage service
 */
class DoctrineObject extends OriginalDoctrineObject
{

    /**
     * Handle various type conversions that should be supported natively by Doctrine (like DateTime)
     *
     * @access protected
     * @param  mixed  $value
     * @param  string $typeOfField
     * @return DateTime
     */
    protected function handleTypeConversions($value, $typeOfField)
    {
        switch ($typeOfField) {
            case 'datetimetz':
            case 'datetime':
            case 'time':
            case 'date':
                if ('' === $value) {
                    return null;
                }

                if (is_int($value)) {
                    $dateTime = new DateTime();
                    $dateTime->setTimestamp($value);
                    $value = $dateTime;
                } elseif (is_string($value)) {
                    if(preg_match("#[\d]+/[\d]+/[\d]+#", $value)){
                        $value = \DateTime::createFromFormat(Time::DATE_FORMAT, $value);
                    }elseif(preg_match("#[\d]+\:[\d]+#", $value)){
                        $value = new \DateTime(Time::UNIX_DATE_STRING." ".$value);
                    }else{
                        $value = new \DateTime($value);
                    }
                }

                break;
            default:
        }

        return $value;
    }

}
