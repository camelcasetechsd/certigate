<?php

namespace Utilities\Service;

use Zend\Uri\Uri as ZendUri;

/**
 * Uri
 * 
 * Generic URI handler
 * 
 * @property string $uri
 * @property bool $acceptEmptyUri
 * 
 * @package utilities
 * @subpackage service
 */
class Uri extends ZendUri
{

    /**
     * @var string
     */
    protected $uri;

    /**
     * @var bool
     */
    protected $acceptEmptyUri;

    /**
     * Create a new URI object
     * 
     * @access public
     * @param  Uri|string|null $uri
     * @param  bool $acceptEmptyUri ,default is false
     * @throws Exception\InvalidArgumentException
     */
    public function __construct($uri = null, $acceptEmptyUri = false)
    {
        parent::__construct($uri);
        $this->acceptEmptyUri = $acceptEmptyUri;
    }

    /**
     * Check if the URI is a valid relative URI
     *
     * @access public
     * @return bool
     */
    public function isValidRelative()
    {
        if ($this->isValidEmptyUri() === true) {
            $isValidRelative = true;
        }
        else {
            $isValidRelative = parent::isValidRelative();
            if ($this->path && $this->isValidEmptyUri() === false && (substr($this->path, 0, 1) != '/' || strpos($this->path, " ") !== false)) {
                $isValidRelative = false;
            }
        }
        return $isValidRelative;
    }

    /**
     * Check if the URI is valid empty one
     *
     * @access public
     * @return bool if uri is a valid empty one
     */
    public function isValidEmptyUri()
    {
        $isValid = false;
        if ($this->acceptEmptyUri === true && $this->uri == "#") {
            $isValid = true;
        }
        return $isValid;
    }

    /**
     * Check if the URI is valid
     *
     * Note that a relative URI may still be valid
     *
     * @access public
     * @return bool
     */
    public function isValid()
    {
        if ($this->isValidEmptyUri() === true) {
            $isValid = true;
        }
        else {
            $isValid = parent::isValid();
        }
        return $isValid;
    }

    /**
     * Parse a URI string
     *
     * @access public
     * @param  string $uri
     * @return Uri
     */
    public function parse($uri)
    {
        $this->uri = $uri;
        return parent::parse($uri);
    }

}
