<?php

namespace Utilities\Service\View\Helper;

use Zend\Form\View\Helper\FormFile as ZendFormFile;
use Zend\Form\ElementInterface;
use Zend\Form\Exception;

/**
 * FormFile
 * 
 * Extend Zend FormFile View Helper
 * Handle file input when file already stored during previous validation file
 * (See: File PRG Plugin)
 * 
 * @package utilities
 * @subpackage service
 */
class FormFile extends ZendFormFile
{

    /**
     * Render a form <input> element from the provided $element
     *
     * @param  ElementInterface $element
     * @throws Exception\DomainException
     * @return string
     */
    public function render(ElementInterface $element)
    {
        // Check to see if a valid file has already been uploaded (from a previous request)
        $hasFile = (bool)$element->getValue();

        // Render the normal file input if we don't have a file uploaded
        if (!$hasFile) {
            return parent::render($element);
        }

        // Ok, we have a file uploaded so its no longer mandatory to upload another one...

        // Set "required" attribute & option to false
        $element->setAttribute('required', false);
        $element->setOption('required', false);
        $fileUploadHtml = parent::render($element);

        $fieldName = $element->getName();
        $fileName = $element->getValue()['name'];

        // Render a readonly section showing the previously uploaded filename
        // As well as the normal file field (hidden)
        // With a link "Change file" toggling the file field to be shown again
        return sprintf(
            "<span id=\"%s_readonly_holder\">%s <a href=\"javascript:;\" style=\"margin-left:10px;\" onclick=\"$('#%s_file_holder').show();$('#%s_readonly_holder').hide();\">Change file</a></span><span id=\"%s_file_holder\" style=\"display:none;\">%s</span>",
            $fieldName,
            $fileName,
            $fieldName,
            $fieldName,
            $fieldName,
            $fileUploadHtml
        );
    }

}
