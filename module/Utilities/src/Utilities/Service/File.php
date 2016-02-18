<?php

namespace Utilities\Service;

use Zend\Http\Response\Stream;
use Zend\Http\Headers;
use Zend\Http\Response;

/**
 * File
 * 
 * Handles File-related operations
 * 
 * @package utilities
 * @subpackage service
 */
class File
{

    /**
     * Get file stream response
     * 
     * 
     * @access public
     * @param string $file
     * @return Stream
     */
    public function getFileResponse($file)
    {
        $response = new Response();
        if (!empty($file) && file_exists($file)) {
            $response = new Stream();
            $response->setStream(fopen($file, 'r'));
            $response->setStatusCode(200);
            $response->setStreamName(basename($file));
            $headers = new Headers();
            $headers->addHeaders(array(
                'Content-Disposition' => 'attachment; filename="' . basename($file) . '"',
                'Content-Type' => 'application/octet-stream',
                'Content-Length' => filesize($file),
                'Expires' => '@0', // @0, because zf2 parses date as string to \DateTime() object
                'Cache-Control' => 'must-revalidate',
                'Pragma' => 'public'
            ));
            $response->setHeaders($headers);
        }
        return $response;
    }

}
