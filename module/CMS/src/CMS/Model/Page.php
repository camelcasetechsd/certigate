<?php

namespace CMS\Model;

use CMS\Entity\Page as PageEntity;

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
    
    /**
     * Prepare logs
     * 
     * @access public
     * @param array $logs
     * @return array logs prepared for display
     */
    public function prepareHistory($logs) {
        $dummyPage = new PageEntity();
        foreach($logs as &$log){
            foreach($log['data'] as $dataKey => &$dataValue){
                if($dataKey == "body"){
                    $dummyPage->body = $dataValue;
                    $dataValue = $dummyPage->getBody();
                }
            }
            
        }
        return $logs;
    }


}
