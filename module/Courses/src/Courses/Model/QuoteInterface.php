<?php

namespace Courses\Model;

/**
 * Quote Interface
 * 
 * Responsible for Quote contract
 * 
 * @package courses
 * @subpackage model
 */
interface QuoteInterface
{

    /**
     * Save quote dependencies before quote
     * 
     * @access public
     * @param mixed $quote
     * @param array $data
     * 
     */
    public function preSave($quote, $data);

    /**
     * Save quote dependencies after quote
     * 
     * @access public
     * @param mixed $quote
     * @param array $data
     */
    public function postSave($quote, $data);

    /**
     * Validate quote form
     * 
     * @access public
     * @param mixed $form
     * @param mixed $quote
     * @param array $data
     * 
     * @return bool true as form is always valid
     */
    public function isQuoteFormValid($form, $quote, $data);

    /**
     * Validate reservation form
     * 
     * @access public
     * @param mixed $form
     * 
     * @return bool validation result
     */
    public function isReservationValid($form);

}
