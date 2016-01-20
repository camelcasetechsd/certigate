<?php

namespace Courses\Model;

/**
 * Resource Model
 * 
 * Handles Resource Entity related business
 * 
 * 
 * @property Utilities\Service\Query\Query $query
 * 
 * @package courses
 * @subpackage model
 */
class Resource
{

    /**
     *
     * @var Utilities\Service\Query\Query 
     */
    protected $query;

    /**
     * Set needed properties
     * 
     * @access public
     * @param Utilities\Service\Query\Query $query
     */
    public function __construct($query)
    {
        $this->query = $query;
    }

    /**
     * Get course resource
     * 
     * @access public
     * @param Courses\Entity\Course $course
     * @param string $resource resource name
     * @param string $name file name
     * 
     * @return string file path
     * @throws \Exception File not found
     */
    public function getResource($course, $resource, $name)
    {
        $resourceGetter = "get" . ucfirst($resource);
        $resources = $course->$resourceGetter();
        if (!isset($resources["tmp_name"])) {
            foreach ($resources as $resource) {
                if ($resource["name"] == $name) {
                    $file = $resource["tmp_name"];
                }
            }
        }
        else {
            $file = $resources["tmp_name"];
        }
        if (!isset($file)) {
            throw new \Exception("File not found");
        }
        return $file;
    }

}
