<?php

namespace Utilities\Service;

/**
 * Status
 * 
 * Hold Status related constants
 * 
 * 
 * @package utilities
 * @subpackage service
 */
class Status {

    
    /**
     * Entity is active
     */
    const STATUS_ACTIVE = 1;
    
    /**
     * Entity is active text
     */
    const STATUS_ACTIVE_TEXT = "Active";
    
    /**
     * Entity is inactive
     */
    const STATUS_INACTIVE = 0;
    
    /**
     * Entity is inactive text
     */
    const STATUS_INACTIVE_TEXT = "Inactive";
    
    /**
     * Entity is deleted
     */
    const STATUS_DELETED = 2;
    
    /**
     * Entity is deleted text
     */
    const STATUS_DELETED_TEXT = "Deleted";
    
    /**
     * Entity is not approved
     */
    const STATUS_NOT_APPROVED = 3;
    
    /**
     * Entity is not approved text
     */
    const STATUS_NOT_APPROVED_TEXT = "Not Approved";

}
