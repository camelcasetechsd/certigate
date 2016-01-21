<?php

namespace Courses\Model;

use Utilities\Service\Status;

/**
 * Resource Model
 * 
 * Handles Resource Entity related business
 * 
 * 
 * @property Utilities\Service\Query\Query $query
 * @property Zend\Log\Logger $logger
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
     *
     * @var Zend\Log\Logger
     */
    protected $logger;

    /**
     * Set needed properties
     * 
     * @access public
     * @param Utilities\Service\Query\Query $query
     * @param Zend\Log\Logger $logger
     */
    public function __construct($query, $logger)
    {
        $this->query = $query;
        $this->logger = $logger;
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
     * Remove resource
     * Remove resource file as well
     * 
     * @access public
     * @param Courses\Entity\Resource $resource
     */
    public function remove($resource)
    {
        $processResult = "true";
        try {
            set_error_handler(function ($errorSeverity, $errorMessage) {
                throw new \Exception($errorMessage, $errorSeverity);
            });
            $file = $resource->getFile()["tmp_name"];
            unlink($file);
            restore_error_handler();
            $this->query->remove($resource);
        } catch (\Exception $e) {
            $this->logger->err($e->getMessage());
            $processResult = "false";
        }

        return $processResult;
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
            if ($resource->getStatus() != Status::STATUS_ACTIVE) {
                continue;
            }
            $preparedResources[$resource->getType()]["files"][] = array(
                "name" => $resource->getName(),
                "id" => $resource->getId(),
            );
            $preparedResources[$resource->getType()]["type"] = $resource->getType();
        }
        return array_values($preparedResources);
    }

}
