<?php

namespace Courses\Model;

use Zend\Authentication\AuthenticationService;
use Users\Entity\Role;
use Utilities\Service\Status;

class Evaluation
{

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
    public function __construct($query)
    {
        $this->query = $query;
    }

    public function saveQuestion($questionObject, $data = 0 , $evaluation = null)
    {
        $questionObject->setToEvaluation($evaluation);
        $this->query->setEntity("Courses\Entity\Question")->save($questionObject, $data);
    }

    public function saveEvaluation($evalObj, $courseId = 0)
    {
        // if evaluation is admin template
        if ($evalObj->isTemplate()) {
            $this->query->setEntity("Courses\Entity\Evaluation")->save($evalObj);
        }
        // id evaluation is user's (atp)
        else {
            $this->query->setEntity("Courses\Entity\Evaluation")->save($evalObj);
            //the only evaluation that has no course_id
            $course = $this->query->findOneBy("Courses\Entity\Evaluation", array(
                'course_id' => null
            ));
            var_dump("inestigate Here if object will get the id after presist");
            exit;
            $course->setEvaluation();
        }
    }

    public function assignQuestionToEvaluation($question)
    {
        $evaluation = $this->query->findOneBy("Courses\Entity\Evaluation", array(
            'isTemplate' => 1
        ));
        $questionEntity = new \Courses\Entity\Question();
        $this->saveQuestion($questionEntity, $question, $evaluation);
    }

    public function removeQuestion($questionTitle)
    {
        $question = $this->query->findOneBy("Courses\Entity\Question", array(
            'questionTitle' => $questionTitle
        ));
        $this->query->remove($question);
    }

    public function updateQuestion($oldQuestionTitle, $newQuestionTitle)
    {
        $question = $this->query->findOneBy("Courses\Entity\Question", array(
            'questionTitle' => $oldQuestionTitle
        ));
        $question->setQuestionTitle($newQuestionTitle);
        $this->query->save($question);
    }

    public function validateQuestion($question)
    {
        $messages = array();
        $stringValidator = new \Zend\I18n\Validator\Alnum(array('allowWhiteSpace' => true));
        $lengthValidator = new \Zend\Validator\StringLength(array('min' => 10));
        // start question validation
        $isStringValid = $stringValidator->isValid($question);
        $isLengthValid = $lengthValidator->isValid($question);
        // check if string
        if (!$isStringValid) {
            array_push($messages, "Please insert valid questions");
        }
        // check on length
        if (!$isLengthValid) {
            array_push($messages, "question must not be less than 10 charachters");
        }
        
        return $messages;
    }

}
