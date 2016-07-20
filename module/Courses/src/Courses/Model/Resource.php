<?php

namespace Courses\Model;

use Utilities\Service\Status;
use Zend\Filter\File\RenameUpload;
use Utilities\Form\FormElementErrors;
use System\Service\Cache\CacheHandler;
use System\Service\Settings;
use Notifications\Service\MailTemplates;
use Notifications\Service\MailSubjects;
use Courses\Entity\Resource as ResourceEntity;
use Courses\Entity\ResourceType as ResourceType;

/**
 * Resource Model
 * 
 * Handles Resource Entity related business
 * 
 * 
 * @property Utilities\Service\Query\Query $query
 * @property Zend\Log\Logger $logger
 * @property System\Service\Cache\CacheHandler $systemCacheHandler
 * @property Notifications\Service\Notification $notification
 * @property Translation\Service\TranslatorHandler $translationHandler
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
     *
     * @var System\Service\Cache\CacheHandler
     */
    protected $systemCacheHandler;

    /**
     *
     * @var Notifications\Service\Notification
     */
    protected $notification;

    /**
     *
     * @var Translation\Service\TranslatorHandler
     */
    protected $translationHandler;

    /**
     * Set needed properties
     * 
     * @access public
     * @param Utilities\Service\Query\Query $query
     * @param Zend\Log\Logger $logger
     * @param System\Service\Cache\CacheHandler $systemCacheHandler
     * @param Notifications\Service\Notification $notification
     * @param Translation\Service\TranslatorHandler $translationHandler
     */
    public function __construct($query, $logger, $systemCacheHandler, $notification, $translationHandler)
    {
        $this->query = $query;
        $this->logger = $logger;
        $this->systemCacheHandler = $systemCacheHandler;
        $this->notification = $notification;
        $this->translationHandler = $translationHandler;
    }

    /**
     * Save resource
     * 
     * @access public
     * @param Courses\Entity\Resource $resource
     * @param array $data ,default is empty array
     * @param bool $isAdminUser ,default is bool false
     * @param string $userEmail ,default is null
     */
    public function save($resource, $data = array(), $isAdminUser = false, $userEmail = null)
    {
        $notifyAdminFlag = false;
        $editFlag = false;
        if (empty($data)) {
            $editFlag = true;
        }

        if ($isAdminUser === false) {
            $resource->setStatus(Status::STATUS_NOT_APPROVED);
            $notifyAdminFlag = true;
        }

        $this->query->setEntity('Courses\Entity\Resource')->save($resource, $data);
        if (isset($data["fileAdded"])) {
            // save added resources
            foreach ($data["fileAdded"] as $fileKey => $fileValue) {

                $filter = new RenameUpload($fileValue["uploadOptions"]);
                $resource = clone $resource;
                $uploadedFile = $filter->filter($fileValue);
                $resource->setFile($uploadedFile);
                $resource->setName($data["nameAdded"][$fileKey]);
                $resource->setNameAr($data["nameArAddedAr"][$fileKey]);
                $this->query->setEntity('Courses\Entity\Resource')->save($resource);
            }
        }

        if ($notifyAdminFlag === true) {
            $this->sendMail($userEmail, $editFlag);
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
        $oneFileTypes = ResourceEntity::$oneFileTypes;

        $validationOutput = array();
        // prepare data for validation
        $courseId = $data["course"];
        $validatedFields = array("name", "file");
        // store data needed to reset form
        $originalData = $data;
        $originalFilter = $form->getInputFilter();
        $isValid = true;
        $moreThanOneResource = false;
        // validate each added resource
        if (isset($data["nameAdded"]) && isset($data["nameArAddedAr"]) && isset($data["fileAdded"]) &&
                is_array($data["nameAdded"]) && is_array($data["nameArAddedAr"]) && is_array($data["fileAdded"]) &&
                (count($data["nameAdded"]) == count($data["nameArAddedAr"]) && count($data["nameArAddedAr"]) == count($data["fileAdded"]))) {
            $moreThanOneResource = true;
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
                $nameArValue = $data["nameArAddedAr"][$nameKey];
                $data["nameAr"] = $nameArValue;
                $validationOutput["addedResources"][$nameKey]["name"]["value"] = $nameValue;
                $validationOutput["addedResources"][$nameKey]["nameAr"]["value"] = $nameArValue;
                $data["file"] = $data["fileAdded"][$nameKey];
                $data["fileAdded"][$nameKey]["uploadOptions"] = array();
                // update input filter after changing input values
                $form->setInputFilter($resource->getInputFilter($courseId, $nameValue, /* $overrideFilterFlag = */ true, /* $fileUploadOptions = */ $data["fileAdded"][$nameKey]["uploadOptions"]));
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
        $currentTypeId = $form->get("type")->getValue();
        $currentType = $this->getResourceTypeTitle($currentTypeId);
        if (in_array($currentType, $oneFileTypes)) {
            $moreThanOneFileType = false;
            if ($moreThanOneResource === true) {
                $moreThanOneFileType = true;
            }
            else {
                $existingResource = $this->query->findOneBy("Courses\Entity\Resource", array("type" => $currentTypeId, "course" => $courseId));
                if (!is_null($existingResource)) {
                    $moreThanOneFileType = true;
                }
            }
            if ($moreThanOneFileType === true) {
                $form->get("type")->setMessages(array("{$currentType} Type can not accept more than one file"));
                $isValid = false;
            }
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
     * Prepare resources for display (not used any more)
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
            $type = $resource->getType()->getTitle();
            $preparedResources[$type]["files"][] = array(
                "name" => $resource->getName(),
                "id" => $resource->getId(),
            );
            
            $preparedResources[$type]["type"] = $type;
        }
        return array_values($preparedResources);
    }

    /**
     * Update listed resources
     * 
     * @access public
     * @param array $dataArray
     * @param bool $isAdminUser
     * @param string $userEmail
     * @param int $courseId
     * 
     * @throws \Exception Type can not accept more than one file
     */
    public function updateListedResources($dataArray, $isAdminUser, $userEmail, $courseId)
    {

        if (isset($dataArray['editedType'])) {
            $editedResourceType = $dataArray['editedType'];

            /**
             * validate number of requested changes to make sure that user ask
             * for more than one type of "One type files" 
             */
            $valuesCount = array_count_values($editedResourceType);
            foreach ($valuesCount as $key => $value) {
                $selectedTypeTitle = $this->getResourceTypeTitle($key);
                if (in_array($selectedTypeTitle, ResourceEntity::$oneFileTypes) && $value > 1) {
                    throw new \Exception("$selectedTypeTitle Type can not accept more than one file");
                }
                else {
                    if (in_array($selectedTypeTitle, ResourceEntity::$oneFileTypes)) {
                        $existingResource = $this->query->findOneBy("Courses\Entity\Resource", array("type" => $key, "course" => $courseId));
                        if (!is_null($existingResource)) {
                            throw new \Exception("$selectedTypeTitle Type can not accept more than one file , already one exists");
                        }
                    }
                }
            }
        }

        if (isset($dataArray['editedName'])) {
            $editedResourceNames = $dataArray['editedName'];
            foreach ($editedResourceNames as $key => $name) {
                $resource = $this->query->findOneBy('Courses\Entity\Resource', array(
                    'id' => $key
                ));
                $resource->setName($name);
                if ($isAdminUser === false) {
                    $resource->setStatus(Status::STATUS_NOT_APPROVED);
                }
                if (isset($dataArray['editedType'][$key])) {
                    $resource->setType($dataArray['editedType'][$key]);
                    unset($dataArray['editedType'][$key]);
                }
                if (isset($dataArray['editedNameAr'][$key])) {
                    $resource->setNameAr($dataArray['editedNameAr'][$key]);
                    unset($dataArray['editedNameAr'][$key]);
                }
                $this->query->save($resource);
            }
        }

        if (isset($dataArray['editedNameAr'])) {
            $editedResourceNames = $dataArray['editedNameAr'];
            foreach ($editedResourceNames as $key => $name) {
                $resource = $this->query->findOneBy('Courses\Entity\Resource', array(
                    'id' => $key
                ));
                $resource->setNameAr($name);
                if ($isAdminUser === false) {
                    $resource->setStatus(Status::STATUS_NOT_APPROVED);
                }
                if (isset($dataArray['editedType'][$key])) {
                    $resource->setType($dataArray['editedType'][$key]);
                    unset($dataArray['editedType'][$key]);
                }
                if (isset($dataArray['editedName'][$key])) {
                    $resource->setName($dataArray['editedName'][$key]);
                    unset($dataArray['editedName'][$key]);
                }

                $this->query->save($resource);
            }
        }

        if (isset($dataArray['editedType'])) {
            $editedResourceType = $dataArray['editedType'];
            foreach ($editedResourceType as $key => $type) {
                $resource = $this->query->findOneBy('Courses\Entity\Resource', array(
                    'id' => $key
                ));
                $newType = $this->query->findOneBy('Courses\Entity\ResourceType', array(
                    'id' => $type
                ));
                $resource->setType($newType);
                if ($isAdminUser === false) {
                    $resource->setStatus(Status::STATUS_NOT_APPROVED);
                }
                $this->query->save($resource);
            }
        }

        if ($isAdminUser === false) {
            $this->sendMail($userEmail, /* $editFlag = */ true);
        }
    }

    /**
     * function to return title of specified resource type 
     * @param string $typeId
     */
    public function getResourceTypeTitle($typeId)
    {
        return $this->query->findOneBy('Courses\Entity\ResourceType', array(
                    'id' => $typeId
                ))->getTitle();
    }

    public function listResourcesForEdit($resources)
    {
        foreach ($resources as $resource) {
            switch ($resource->getType()->getId()) {
                case 1:
                    $resource->setType(ResourceType::PRESENTATIONS_TYPE_TEXT);
                    break;
                case 2:
                    $resource->setType(ResourceType::ACTIVITIES_TYPE_TEXT);
                    break;
                case 3:
                    $resource->setType(ResourceType::EXAMS_TYPE_TEXT);
                    break;
            }
        }
        return $resources;
    }

    /**
     * Get translated resources types
     * 
     * @access public
     * @return array translated resource types
     */
    public function getTranslatedResourceTypes()
    {
        $typeValueOptions = array_combine(/* $keys = */ ResourceEntity::$types, /* $values = */ ResourceEntity::$types);
        foreach ($typeValueOptions as &$type) {
            $type = $this->translationHandler->translate($type);
        }
        return $typeValueOptions;
    }

    /**
     * Send mail
     * 
     * @access private
     * @param string $userEmail
     * @param bool $editFlag
     * @throws \Exception From email is not set
     * @throws \Exception To email is not set
     */
    private function sendMail($userEmail, $editFlag)
    {
        $forceFlush = (APPLICATION_ENV == "production" ) ? false : true;
        $cachedSystemData = $this->systemCacheHandler->getCachedSystemData($forceFlush);
        $settings = $cachedSystemData[CacheHandler::SETTINGS_KEY];

        if (array_key_exists(Settings::SYSTEM_EMAIL, $settings)) {
            $from = $settings[Settings::SYSTEM_EMAIL];
        }
        if (array_key_exists(Settings::ADMIN_EMAIL, $settings)) {
            $to = $settings[Settings::ADMIN_EMAIL];
        }

        if (!isset($from)) {
            throw new \Exception("From email is not set");
        }
        if (!isset($to)) {
            throw new \Exception("To email is not set");
        }
        $templateParameters = array(
            "email" => $userEmail,
        );

        if ($editFlag === false) {
            $templateName = MailTemplates::NEW_RESOURCE_NOTIFICATION_TEMPLATE;
            $subject = MailSubjects::NEW_RESOURCE_NOTIFICATION_SUBJECT;
        }
        else {
            $templateName = MailTemplates::UPDATED_RESOURCE_NOTIFICATION_TEMPLATE;
            $subject = MailSubjects::UPDATED_RESOURCE_NOTIFICATION_SUBJECT;
        }

        $mailArray = array(
            'to' => $to,
            'from' => $from,
            'templateName' => $templateName,
            'templateParameters' => $templateParameters,
            'subject' => $subject,
        );
        $this->notification->notify($mailArray);
    }

}
