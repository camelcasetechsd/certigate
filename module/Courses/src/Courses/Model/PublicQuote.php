<?php

namespace Courses\Model;


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


}
