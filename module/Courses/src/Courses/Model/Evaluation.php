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

    /**
     * this function to save & update question 
     * @param Question $questionObject
     * @param array $data
     * @param Evaluation $evaluation
     */
    public function saveQuestion($questionObject, $data = 0, $evaluation = null)
    {
        $questionObject->setToEvaluation($evaluation);
        $this->query->setEntity("Courses\Entity\Question")->save($questionObject, $data);
    }

    /**
     * this function is meant to save evaluation no matters it's type 
     * template or course evaluation
     * @param Evaluation $evalObj
     * @param int $courseId not required if template
     */
    public function saveEvaluation($evalObj, $courseId = null)
    {
        // if evaluation is admin template
        if ($evalObj->isTemplate()) {
            $this->query->setEntity("Courses\Entity\Evaluation")->save($evalObj);
        }
        // id evaluation is user's (atp)
        else {
            //find the course to assign it to the evaluation
            $course = $this->query->findOneBy("Courses\Entity\Course", array(
                'id' => $courseId
            ));
            //assign course to evaluation
            $evalObj->setCourse($course);

            $this->query->setEntity('Courses\Entity\Evaluation')->save($evalObj);
            
        }
    }

    /**
     * this function is meant to assign questions to an specific evaluation
     * if they are questions for admin template or course evaluation 
     * 
     * @param string $question question title
     * @param int $evaluationId not required if saving admin template
     */
    public function assignQuestionToEvaluation($question, $evaluationId = 0)
    {
        // for admin evaluation ... note admin evaaluation will not be 0
        // but for sake of useing generic functions
        if ($evaluationId == 0) {
            // getting the only template with property isTemplate = 1
            // which is admin template
            $evaluation = $this->query->findOneBy("Courses\Entity\Evaluation", array(
                'isTemplate' => 1
            ));
        }
        else {
            $evaluation = $this->query->findOneBy("Courses\Entity\Evaluation", array(
                'id' => $evaluationId
            ));
        }
        $questionEntity = new \Courses\Entity\Question();

        $questionEntity->setQuestionTitle($question);
        $questionEntity->setToEvaluation($evaluation);
            $evaluation->addQuestion($questionEntity);
        $this->query->save($evaluation);
    }

    public function removeQuestion($questionTitle)
    {
        $question = $this->query->findOneBy("Courses\Entity\Question", array(
            'questionTitle' => $questionTitle
        ));
        $this->query->remove($question);
    }

    public function updateQuestion($oldQuestionTitle, $newQuestionTitle,$evaluationId)
    {
        $question = $this->query->findOneBy("Courses\Entity\Question", array(
            'questionTitle' => $oldQuestionTitle,
            'evaluation' => $evaluationId
        ));
        $question->setQuestionTitle($newQuestionTitle);
        $this->query->save($question);
    }

    public function validateQuestion($questions)
    {
        $messages = array();
        $stringValidator = new \Zend\Validator\Regex('/^a-zA-Z0-9 \?|\s/');

        foreach ($questions as $question) {
            // start question validation
            $isStringValid = $stringValidator->isValid($question);
            // check if string
            if (!$isStringValid) {
                array_push($messages, $question." : is not a valid question ... please insert a valid one");
            }
        }
        return $messages;
    }

    /**
     * function meant to check question title existance in specific 
     * evaluation because question can not be unique for all evaluations
     *  
     * @param type $evaluation  evaluation object
     * @param type $questionTitle  string of questionTitle wanted to be saved
     * @return boolean  true if not exists  /  false if exists
     */
    public function checkQuestionExistanceInEvalautaion($evaluation, $questionTitle)
    {
        $questions = $evaluation->getQuestions();
        foreach ($questions as $question) {
            if ($question->questionTitle === $questionTitle) {
                return FALSE;
            }
        }
        return TRUE;
    }

}
