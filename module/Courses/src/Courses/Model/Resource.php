<?php

namespace Courses\Model;

use Utilities\Service\Status;
use Zend\Filter\File\RenameUpload;
use Utilities\Form\FormElementErrors;

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
        // save added resources
        foreach ($data["fileAdded"] as $fileKey => $fileValue) {
            
                $filter = new RenameUpload($fileValue["uploadOptions"]);
                $resource = clone $resource;
                $uploadedFile = $filter->filter($fileValue);
                $resource->setFile($uploadedFile);
                $resource->setName($data["nameAdded"][$fileKey]);
                $this->query->setEntity('Courses\Entity\Resource')->save($resource);
        }
    }

    /**
     * Validate resources
     * 
     * @access public
     * @param Courses\Form\ResourceForm $form
     * @param Courses\Entity\Resource $resource
     * @param array $data ,passed by reference
     * 
     * @return array validation output
     */
    public function validateResources($form, $resource, &$data)
    {
        $formErrors = new FormElementErrors();
        
        $validationOutput = array();
        // prepare data for validation
        $courseId = $data["course"];
        $validatedFields = array("name", "file");
        // store data needed to reset form
        $originalData = $data;
        $originalFilter = $form->getInputFilter();
        $isValid = true;
        // validate each added resource
        if (isset($data["nameAdded"]) && isset($data["fileAdded"]) &&
                is_array($data["nameAdded"]) && is_array($data["fileAdded"]) &&
                count($data["nameAdded"]) == count($data["fileAdded"])) {
            foreach ($data["nameAdded"] as $nameKey => $nameValue) {
                foreach ($validatedFields as $validatedField) {
                    $validationOutput["addedResources"][$nameKey][$validatedField] = array(
                        "messages" => '',
                        "errorsMarkup" => '',
                        "errorClass" => '',
                        "value" => '',
                    );
                }
                // manipulate data passed to form as if added resource is the original one
                $data["name"] = $nameValue;
                $validationOutput["addedResources"][$nameKey]["name"]["value"] = $nameValue;
                $data["file"] = $data["fileAdded"][$nameKey];
                $data["fileAdded"][$nameKey]["uploadOptions"] = array();
                // update input filter after changing input values
                $form->setInputFilter($resource->getInputFilter($courseId, $nameValue, /*$overrideFilterFlag =*/ true, /*$fileUploadOptions =*/ $data["fileAdded"][$nameKey]["uploadOptions"]));
                $form->setData($data);
                // validate added resource
                $isValid &= $form->isValid();
                $messages = $form->getMessages();
                foreach ($validatedFields as $validatedField) {
                    // add error messages -if exist-
                    // generate errors markup to be used in display directly
                    if (array_key_exists($validatedField, $messages)) {
                        $validationOutput["addedResources"][$nameKey][$validatedField]["messages"] = $messages[$validatedField];
                        $validationOutput["addedResources"][$nameKey][$validatedField]["errorsMarkup"] = $formErrors->render($form->get($validatedField));
                        $validationOutput["addedResources"][$nameKey][$validatedField]["errorClass"] = "input-error";
                    }
                }
            }
        }
        // reset Form data
        $form->setData($originalData);
        $form->setInputFilter($originalFilter);
        foreach ($validatedFields as $validatedField) {
            $form->get($validatedField)->setMessages(array());
        }
        // validate original resource
        $isValid &= $form->isValid();
        
        $validationOutput["isValid"] = $isValid;
        return $validationOutput;
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
