<?php

namespace Utilities\Service;

use Utilities\Form\FormButtons;

/**
 * Status
 * 
 * Hold Status related constants
 * 
 * 
 * @package utilities
 * @subpackage service
 */
class Status
{

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

    /**
     * Entity is state saved
     */
    const STATUS_STATE_SAVED = 4;

    /**
     * Entity is state saved text
     */
    const STATUS_STATE_SAVED_TEXT = "State Saved";

    /**
     * Entity is pending pricing
     */
    const STATUS_PENDING_PRICING = 5;

    /**
     * Entity is pending pricing text
     */
    const STATUS_PENDING_PRICING_TEXT = "Pending Pricing";

    /**
     * Entity is pending payment
     */
    const STATUS_PENDING_PAYMENT = 6;

    /**
     * Entity is pending payment text
     */
    const STATUS_PENDING_PAYMENT_TEXT = "Pending Payment";

    /**
     * Entity is pending review
     */
    const STATUS_PENDING_REVIEW = 7;

    /**
     * Entity is pending review text
     */
    const STATUS_PENDING_REVIEW_TEXT = "Pending Review";

    /**
     * Entity is pending repayment
     */
    const STATUS_PENDING_REPAYMENT= 8;

    /**
     * Entity is pending repayment text
     */
    const STATUS_PENDING_REPAYMENT_TEXT = "Pending Repayment";

    /**
     * Entity is cancelled
     */
    const STATUS_CANCELLED= 9;

    /**
     * Entity is cancelled text
     */
    const STATUS_CANCELLED_TEXT = "Cancelled";
    
    /**
     * Set status
     * 
     * @access public
     * @param object $object
     * @param array $data
     * @param bool $editFlag ,default is false
     */
    public static function setStatus($object, $data, $editFlag = false){
        $buttonsData = $data["buttons"];
        if (array_key_exists(FormButtons::SAVE_AND_PUBLISH_BUTTON, $buttonsData)) {
            $object->setStatus(self::STATUS_ACTIVE);
        }
        elseif (array_key_exists(FormButtons::UNPUBLISH_BUTTON, $buttonsData)) {
            $object->setStatus(self::STATUS_INACTIVE);
        }
        elseif (array_key_exists(FormButtons::SAVE_BUTTON, $buttonsData) && $editFlag === false) {
            $object->setStatus(self::STATUS_INACTIVE);
        }
    }

    /**
     * Entity is not yet expired(organization)
     */
    const STATUS_NOT_YET_EXPIRED = "0";

    /**
     * Entity is expired(organization)
     */
    const STATUS_EXPIRED = "1";

}
