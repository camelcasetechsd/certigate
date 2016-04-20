<?php

namespace Utilities\Service;

/**
 * Distance
 * 
 * Handles Distance-related operations
 * 
 * @package utilities
 * @subpackage service
 */
class Distance
{

    /**
     * Calculates the great-circle distance between two points, with
     * the Haversine formula 'Haversine Great Circle Distance'
     * Unit of calculated distance is the same as earth's radius
     * @access public
     * @param float $latitudeFrom Latitude of start point in [deg decimal]
     * @param float $longitudeFrom Longitude of start point in [deg decimal]
     * @param float $latitudeTo Latitude of target point in [deg decimal]
     * @param float $longitudeTo Longitude of target point in [deg decimal]
     * @param float $earthRadius Mean earth radius in [km]
     * @return float Distance between points in [km] (same as earthRadius)
     */
    function getDistance($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $earthRadius = 6371)
    {
        // convert from degrees to radians
        $latitiudeFromRad = deg2rad($latitudeFrom);
        $longitudeFromRad = deg2rad($longitudeFrom);
        $latitudeToRad = deg2rad($latitudeTo);
        $longitudeToRad = deg2rad($longitudeTo);

        $latitudeDelta = $latitudeToRad - $latitiudeFromRad;
        $longitudeDelta = $longitudeToRad - $longitudeFromRad;

        $angle = 2 * asin(sqrt(pow(sin($latitudeDelta / 2), 2) +
                                cos($latitiudeFromRad) * cos($latitudeToRad) * pow(sin($longitudeDelta / 2), 2)));
        return $angle * $earthRadius;
    }

}
