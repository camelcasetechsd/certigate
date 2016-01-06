<?php

namespace CMS\Model;

/**
 * Page Model
 * 
 * Handles Page Entity related business
 * 
 * 
 * @property Utilities\Service\Query\Query $query
 * 
 * @package cms
 * @subpackage model
 */
class Page {

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
    public function __construct($query) {
        $this->query = $query;
    }


}
