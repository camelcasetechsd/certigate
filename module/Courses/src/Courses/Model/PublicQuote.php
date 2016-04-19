<?php

namespace Courses\Model;

use Utilities\Service\Status;

/**
 * PublicQuote Model
 * 
 * Handles PublicQuote entity related-business
 * 
 * 
 * @property Utilities\Service\Query\Query $query
 * @property Utilities\Service\Object $objectUtilities
 * 
 * @package courses
 * @subpackage model
 */
class PublicQuote implements QuoteInterface
{

    /**
     *
     * @var Utilities\Service\Query\Query
     */
    protected $query;

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
     * @param Utilities\Service\Object $objectUtilities
     */
    public function __construct($query, $objectUtilities)
    {
        $this->query = $query;
        $this->objectUtilities = $objectUtilities;
    }

    /**
     * Save quote dependencies before quote
     * 
     * @access public
     * @param Courses\Entity\PublicQuote $quote
     * @param array $data
     * 
     */
    public function preSave($quote, $data)
    {
        
    }

    /**
     * Save quote dependencies after quote
     * 
     * @access public
     * @param Courses\Entity\PublicQuote $quote
     * @param array $data
     */
    public function postSave($quote, $data)
    {
        if ($quote->getStatus() == Status::STATUS_PENDING_PRICING) {
            $courseEvent = $quote->getCourseEvent();
            $courseEvent->setStudentsNo((int) $courseEvent->getStudentsNo() + (int) $quote->getSeatsNo());
            $courseEventArray = $this->objectUtilities->prepareForSave(array($courseEvent));
            $courseEvent = reset($courseEventArray);
            $this->query->setEntity("Courses\Entity\CourseEvent")->save($courseEvent, /* $data = */ array());
        }
    }

    /**
     * Validate quote form
     * 
     * @access public
     * @param Courses\Form\PublicQuoteForm $form
     * @param Courses\Entity\PublicQuote $quote
     * @param array $data
     * 
     * @return bool true as form is always valid
     */
    public function isQuoteFormValid($form, $quote, $data)
    {
        // Do nothing as there is no quote validation in case of public quote
        return true;
    }

    /**
     * Validate reservation form
     * 
     * @access public
     * @param Courses\Form\PublicQuoteReservationForm $form
     * 
     * @return bool validation result
     */
    public function isReservationValid($form)
    {
        $isValid = true;
        $seatsNo = (int) $form->get("seatsNo")->getValue();
        $courseEventId = $form->get("courseEvent")->getValue();
        $courseEvent = $this->query->find("Courses\Entity\CourseEvent", $courseEventId);
        $studentsNo = (int) $courseEvent->getStudentsNo();
        $capacity = (int) $courseEvent->getCapacity();
        if (($capacity - $studentsNo) < $seatsNo) {
            $isValid = false;
            $form->get("seatsNo")->setMessages(array(
                "Course does not have enough seats"
            ));
        }
        return $isValid;
    }

}
