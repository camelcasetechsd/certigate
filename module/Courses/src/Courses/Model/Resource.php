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
     * Save resource
     * 
     * @access public
     * @param Courses\Entity\Resource $resource
     * @param array $data ,default is empty array
     * @param bool $isAdminUser ,default is bool false
     * @param bool $oldStatus ,default is null
     */
    public function save($resource, $data = array(), $isAdminUser = false, $oldStatus = null)
    {

        if ($isAdminUser === false) {
            // edit case where data is empty array
            if (count($data) == 0) {
                $resource->setStatus($oldStatus);
            }
            else {
                $resource->setStatus(Status::STATUS_NOT_APPROVED);
            }
        }

        $this->query->setEntity('Courses\Entity\Resource')->save($resource, $data);
    }

    /**
     * Prepare resources for display
     * 
     * @access public
     * @param array $resources
     * 
     * @return array prepared resources
     */
    public function prepareResourcesForDisplay($resources)
    {
        $preparedResources = array();
        foreach ($resources as $resource) {
            $preparedResources[$resource->getType()]["files"][] = array(
                "name" => $resource->getName(),
                "id" => $resource->getId(),
            );
            $preparedResources[$resource->getType()]["type"] = $resource->getType();
        }
        return array_values($preparedResources);
    }

}
