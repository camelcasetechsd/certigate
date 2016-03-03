<?php

namespace Utilities\Form;

use Zend\Form\View\Helper\Form as OriginalFormViewHelper;
use Zend\Form\FieldsetInterface;
use Zend\Form\FormInterface;
use Zend\Form\Element\Submit;
use Zend\Form\Element\Button;

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
class FormViewHelper extends OriginalFormViewHelper
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
        $inlineForm = false;
        if (strpos($form->getAttribute('class'), "form-horizontal") === false) {
            $inlineForm = true;
        }
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
        if ($labelAbsent === true 
                && (strpos($element->getAttribute('class'), "btn") === false
                || (strpos($element->getAttribute('class'), "btn") !== false && strpos($element->getAttribute('class'), "pull") === false))
                && $inlineForm === false) {
            $elementContent.= "<dt>&nbsp;</dt>";
        }
        else {
            $divAttributes = "";
            if ($inlineForm === true) {
                $divAttributes = "class='form-group'";
            }
            $elementContent.="<div $divAttributes >";
            $formElementAppendString = '</div>';
        }

        // Change submit button text to edit if form is an edit form
        if ($element instanceof Submit && $form->isEditForm === true) {
            if(property_exists($form, "isAdminUser") && $form->isAdminUser === false && $form->needAdminApproval === true){
                $element->setValue(FormButtons::SUBMIT_FOR_ADMIN_APPROVAL_BUTTON_TEXT);
            }elseif($element->getValue() == FormButtons::CREATE_BUTTON_TEXT){
                $element->setValue(FormButtons::EDIT_BUTTON_TEXT);
            }
        }

        $elementContent.= $this->getView()->formRow($element);
        $elementContent.=$formElementAppendString;

        return $elementContent;
    }

}
