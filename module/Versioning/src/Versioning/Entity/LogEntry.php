<?php

namespace Versioning\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Loggable\Entity\MappedSuperclass\AbstractLogEntry;

/**
 * LogEntry Entity
 *
 * @ORM\Entity(repositoryClass="Versioning\Entity\LogEntryRepository")
 * @ORM\Table(
 *     name="ext_log_entries",
 *  indexes={
 *      @ORM\Index(name="log_class_lookup_idx", columns={"object_class"}),
 *      @ORM\Index(name="log_date_lookup_idx", columns={"logged_at"}),
 *      @ORM\Index(name="log_user_lookup_idx", columns={"username"}),
 *      @ORM\Index(name="log_version_lookup_idx", columns={"object_id", "object_class", "version"})
 *  }
 * )
 * 
 * @package versioning
 * @subpackage entity
 */
class LogEntry extends AbstractLogEntry
{
    /**
     * All required columns are mapped through inherited superclass
     */
    
    /**
     * @ORM\Column(type="integer", nullable=true)
     * @var string
     */
    protected $userId;
    
    /**
     * @ORM\Column(type="integer", nullable=true)
     * @var int
     */
    protected $objectStatus;
    
    /**
     * Get user id
     *
     * @access public
     * @return string
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set userId
     *
     * @access public
     * @param string $userId
     */
    public function setUserId($userId)
    {
        $this->userId = (int)$userId;
    }
    
    
    /**
     * Get object status
     *
     * @access public
     * @return string
     */
    public function getObjectStatus()
    {
        return $this->objectStatus;
    }
    
    /**
     * Set object status
     * 
     * @access public
     * @param int $objectStatus
     */
    public function setObjectStatus($objectStatus)
    {
        $this->objectStatus = $objectStatus;
    }
}
