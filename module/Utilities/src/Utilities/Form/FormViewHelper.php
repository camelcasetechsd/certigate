<?php

namespace Utilities\Form;

use Zend\Form\View\Helper\Form;
use Zend\Form\FieldsetInterface;
use Zend\Form\FormInterface;
use Zend\Form\Element\Submit;

/**
 * FormView Helper
 * 
 * Handles form elements display
 * 
 * 
 *
 * @package utilities
 * @subpackage form
 */
class FormViewHelper extends Form
{

    /**
     * Render a form from the provided $form,
     * 
     * 
     * 
     * @access public
     * @param  FormInterface $form
     * @return string form HTML content
     */
    public function render(FormInterface $form)
    {
        if (method_exists($form, 'prepare')) {
            $form->prepare();
        }

        $formContent = '';

        foreach ($form as $element) {

            if ($element instanceof FieldsetInterface) {
                $formContent.= $this->getView()->formCollection($element);
            }
            else {

                $formContent.= $this->renderElement($form, $element);
            }
        }

        return $this->openTag($form) . $formContent . $this->closeTag();
    }

    /**
     * Render single element
     * 
     * @access public
     * @param FormInterface $form
     * @param Zend\Form\Element $element
     * @return string element HTML content
     */
    public function renderElement($form, $element)
    {
        $elementContent = '';
        // add required class to all required elements
        if (!empty($element->getAttribute('required')) && !$element->getLabelOption("disable_html_escape")) {
            $labelAttributes = $element->getLabelAttributes();
            $labelClass = (isset($labelAttributes["class"])) ? $labelAttributes["class"] : "";
            $labelAttributes["class"] = $labelClass . " required";
            $element->setLabelAttributes($labelAttributes);
        }
        // Add Id to all form elements
        // When element has an Id, Label tag won't enclose form element
        if (empty($element->getAttribute('id'))) {
            $element->setAttribute('id', $form->getAttribute('name') . "_" . $element->getAttribute('name'));
        }
        $labelAbsent = false;
        $formElementAppendString = '';
        if (empty($element->getLabel()) && $element->getAttribute('type') !== "hidden") {
            $labelAbsent = true;
        }
        if ($labelAbsent === true) {
            $elementContent.= "<dt>&nbsp;</dt>";
        }
        else {
            $elementContent.='<dd>';
            $formElementAppendString = '</dd>';
        }

        // Change submit button text to edit if form is an edit form
        if ($element instanceof Submit && $form->isEditForm === true) {
            $element->setValue("Edit");
        }

        $elementContent.= $this->getView()->formRow($element);
        $elementContent.=$formElementAppendString;
        
        return $elementContent;
    }

}
