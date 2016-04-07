<?php

namespace Courses\Model;

use Courses\Entity\PrivateQuote as PrivateQuoteEntity;
use Utilities\Service\Status;
use Courses\Entity\CourseEvent;

/**
 * PrivateQuote Model
 * 
 * Handles PrivateQuote entity related-business
 * 
 * 
 * @property Utilities\Service\Query\Query $query
 * @property Translation\Service\Translator\TranslatorHandler $translationHandler
 * @property Courses\Model\CourseEvent $courseEventModel
 * @property Utilities\Service\Object $objectUtilities
 * 
 * @package courses
 * @subpackage model
 */
class PrivateQuote implements QuoteInterface
{

    /**
     *
     * @var Utilities\Service\Query\Query
     */
    protected $query;

    /**
     *
     * @var Translation\Service\Translator\TranslatorHandler
     */
    protected $translationHandler;

    /**
     *
     * @var Courses\Model\CourseEvent
     */
    protected $courseEventModel;

    /**
     *
     * @var Utilities\Service\Object
     */
    protected $objectUtilities;

    /**
     * Set needed properties
     * 
     * @access public
     * @param Utilities\Service\Query\Query $query
     * @param Translation\Service\Translator\TranslatorHandler $translationHandler
     * @param Courses\Model\CourseEvent $courseEventModel
     * @param Utilities\Service\Object $objectUtilities
     */
    public function __construct($query, $translationHandler, $courseEventModel, $objectUtilities)
    {
        $this->query = $query;
        $this->translationHandler = $translationHandler;
        $this->courseEventModel = $courseEventModel;
        $this->objectUtilities = $objectUtilities;
    }

    /**
     * Save quote dependencies before quote
     * 
     * @access public
     * @param Courses\Entity\PrivateQuote $quote
     * @param array $data
     * 
     */
    public function preSave($quote, $data)
    {
        if ($quote->getStatus() == Status::STATUS_PENDING_PRICING) {
            $data = array(
                "status" => Status::STATUS_ACTIVE,
                "course" => $data["course"],
                "capacity" => 1,
                "studentsNo" => 1,
                "hideFromCalendar" => Status::STATUS_ACTIVE
            );
            $this->query->setEntity("Courses\Entity\CourseEvent")->save($courseEvent = new CourseEvent(), $data);
            $quote->setCourseEvent($courseEvent);
        }
    }

    /**
     * Save quote dependencies after quote
     * 
     * @access public
     * @param Courses\Entity\PrivateQuote $quote
     * @param array $data
     */
    public function postSave($quote, $data)
    {
        if ($quote->getStatus() == Status::STATUS_PENDING_PAYMENT && array_key_exists("courseEvent", $data)) {
            $courseEventData = $data["courseEvent"];
            $courseEvent = $quote->getCourseEvent();
            $courseEventArray = $this->objectUtilities->prepareForSave(array($courseEvent));
            $courseEvent = reset($courseEventArray);
            $this->query->setEntity("Courses\Entity\CourseEvent")->save($courseEvent, /* $data = */ $courseEventData);
        }
    }

    /**
     * Validate reservation form
     * 
     * @access public
     * @param Courses\Form\PrivateQuoteReservationForm $form
     * 
     * @return bool true as form is always valid
     */
    public function isReservationValid($form)
    {
        // Do nothing as there is no reservation validation in case of private quote
        return true;
    }

    /**
     * Validate quote form
     * 
     * @access public
     * @param Courses\Form\PrivateQuoteForm $form
     * @param Courses\Entity\PrivateQuote $quote
     * @param array $data
     * 
     * @return bool true as form is always valid
     */
    public function isQuoteFormValid($form, $quote, $data)
    {
        $isValid = true;
        if ($quote->getStatus() == Status::STATUS_PENDING_PRICING && array_key_exists("courseEvent", $data)) {
            $isValid = (bool) $this->courseEventModel->validateForm(/* $form = */ $form->get("courseEvent"), /* $data = */ $data["courseEvent"]);
        }
        return $isValid;
    }

    /**
     * Get translated venue types
     * 
     * @access public
     * @return array translated venue types
     */
    public function getTranslatedVenueTypes()
    {
        $venues = array(
            PrivateQuoteEntity::VENUE_CUSTOMER_PREMISES,
            PrivateQuoteEntity::VENUE_COMPANY_PREMISES,
            PrivateQuoteEntity::VENUE_OTHER_PREMISES,
        );
        return $this->translationHandler->getTranslatedArray($venues);
    }

}
