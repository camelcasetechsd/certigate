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
 * 
 * @package courses
 * @subpackage model
 */
class PrivateQuote
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
     * Set needed properties
     * 
     * @access public
     * @param Utilities\Service\Query\Query $query
     * @param Translation\Service\Translator\TranslatorHandler $translationHandler
     */
    public function __construct($query, $translationHandler)
    {
        $this->query = $query;
        $this->translationHandler = $translationHandler;
    }

    /**
     * Save quote dependencies before quote
     * 
     * @access public
     * @param Courses\Entity\PrivateQuote $quote
     * @param array $data
     * 
     */
    public function saveDepsBeforeQuote($quote, $data)
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
        $quote->setPreferredDate(new \DateTime($quote->preferredDate));
        $quote->setCreated(new \DateTime($quote->getCreated()));
        if (!is_null($quote->getModified())) {
            $quote->setModified(new \DateTime($quote->getModified()));
        }
    }

    /**
     * Save quote dependencies after quote
     * 
     * @access public
     * @param Courses\Entity\PrivateQuote $quote
     * 
     */
    public function saveDepsAfterQuote($quote)
    {
        
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
