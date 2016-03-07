<?php

namespace Courses\Form;

use Zend\Form\FormInterface;
use Utilities\Form\FormViewHelper as OriginalFormViewHelper;
use DoctrineModule\Form\Element\ObjectSelect;
use Utilities\Service\String;
use Utilities\Service\Status;
use Utilities\Form\Form;

/**
 * FormView Helper
 * 
 * Handles book exam form elements display
 * 
 * 
 *
 * @package courses
 * @subpackage form
 */
class BookExamFormViewHelper extends OriginalFormViewHelper
{

    /**
     * Render a form from the provided $form,
     * 
     * @access public
     * @param  FormInterface $form
     * @return string form HTML content
     */
    public function render(FormInterface $form)
    {
        foreach ($form as $element) {
            // handle course event fields drop down display
            if ($element instanceof ObjectSelect && $element->getName() === "courseEvent") {
                $valueOptions = $element->getValueOptions();
                // get entity manager
                $valueLabels = array();
                foreach ($valueOptions as $valueKey => &$valueOption) {
                    // escape new keys added to $valueOptions array
                    if (in_array($valueKey, $valueLabels)) {
                        continue;
                    }
                    if (is_array($valueOption)) {
                        $valueOption["options"] = array();
                        foreach ($valueOption as $propertyName => $propertyValue) {
                            // process label to get course data
                            if ($propertyName === "label") {
                                $propertyValueItems = explode(/* $delimiter = */ String::TEXT_SEPARATOR, $propertyValue);
                                $courseName = reset($propertyValueItems);
                                $propertyValue = end($propertyValueItems);
                            }
                            // add course event data to options array while removing other keys in course event data array
                            if ($propertyName !== "options") {
                                $valueOption["options"][0][$propertyName] = $propertyValue;
                                unset($valueOption[$propertyName]);
                            }
                        }
                        $valueOption["label"] = $courseName;
                        $valueLabels[] = $courseName;

                        // append option to corresponding course options
                        if (array_key_exists($courseName, $valueOptions)) {
                            $valueOptions[$courseName]["options"][] = $valueOption["options"][0];
                        }
                        else {
                            $valueOptions[$courseName] = $valueOption;
                        }
                        unset($valueOptions[$valueKey]);
                    }
                }
                $element->setValueOptions($valueOptions);
            }
        }
        return parent::render($form);
    }

}
