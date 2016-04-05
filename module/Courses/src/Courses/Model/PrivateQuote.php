<?php

namespace Courses\Model;

use Courses\Entity\PrivateQuote as PrivateQuoteEntity;

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
