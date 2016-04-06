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
 * 
 * @package courses
 * @subpackage model
 */
class PublicQuote
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
     * Save quote dependencies before quote
     * 
     * @access public
     * @param Courses\Entity\PublicQuote $quote
     * @param array $data
     * 
     */
    public function saveDepsBeforeQuote($quote, $data)
    {
        
    }
    
    /**
     * Save quote dependencies after quote
     * 
     * @access public
     * @param Courses\Entity\PublicQuote $quote
     * 
     */
    public function saveDepsAfterQuote($quote)
    {
        if ($quote->getStatus() == Status::STATUS_PENDING_PRICING) {
            $courseEvent = $quote->getCourseEvent();
            $courseEvent->setStudentsNo((int) $courseEvent->getStudentsNo() + (int) $quote->getSeatsNo());
            $courseEvent->setStartDate(new \DateTime($courseEvent->getStartDate()));
            $courseEvent->setEndDate(new \DateTime($courseEvent->getEndDate()));
            $courseEvent->setCreated(new \DateTime($courseEvent->getCreated()));
            if (!is_null($courseEvent->getModified())) {
                $courseEvent->setModified(new \DateTime($courseEvent->getModified()));
            }
            $this->query->setEntity("Courses\Entity\CourseEvent")->save($courseEvent, /* $data = */ array());
        }
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
