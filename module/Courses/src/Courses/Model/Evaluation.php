<?php

namespace Courses\Model;

use System\Service\Cache\CacheHandler;
use System\Service\Settings;
use Notifications\Service\MailTempates;
use Notifications\Service\MailSubjects;
use Utilities\Service\Status;

/**
 * Evaluation Model
 * 
 * Handles Evaluation Entity related business
 * 
 * 
 * @property Utilities\Service\Query\Query $query
 * @property System\Service\Cache\CacheHandler $systemCacheHandler
 * @property Notifications\Service\Notification $notification
 * 
 * @package courses
 * @subpackage model
 */
class Evaluation
{

    /**
     *
     * @var Utilities\Service\Query\Query 
     */
    protected $query;

    /**
     *
     * @var System\Service\Cache\CacheHandler
     */
    protected $systemCacheHandler;

    /**
     *
     * @var Notifications\Service\Notification
     */
    protected $notification;

    /**
     * Set needed properties
     * 
     * @access public
     * @param Utilities\Service\Query\Query $query
     * @param System\Service\Cache\CacheHandler $systemCacheHandler
     * @param Notifications\Service\Notification $notification
     */
    public function __construct($query, $systemCacheHandler, $notification)
    {
        $this->query = $query;
        $this->systemCacheHandler = $systemCacheHandler;
        $this->notification = $notification;
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
     * @param string $userEmail ,default is null
     * @param bool $isAdminUser ,default is true
     * @param bool $editFlag ,default is false
     */
    public function saveEvaluation($evalObj, $courseId = null, $userEmail = null, $isAdminUser = true, $editFlag = false)
    {
        // if evaluation is admin template
        if ($evalObj->isTemplate()) {
            $evalObj->setStatus(Status::STATUS_ACTIVE);
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
            $evalObj->setStatus(Status::STATUS_NOT_APPROVED);

            $this->query->setEntity('Courses\Entity\Evaluation')->save($evalObj);
        }
        if ($isAdminUser === false) {
            $this->sendMail($userEmail, $editFlag);
        }
    }

    /**
     * this function is meant to assign questions to an specific evaluation
     * if they are questions for admin template or course evaluation 
     * 
     * @param string $question question title
     * @param string $questionInArabic question title in arabic
     * @param int $evaluationId not required if saving admin template
     */
    public function assignQuestionToEvaluation($question, $questionInArabic, $evaluationId = 0)
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
        $questionEntity->setQuestionTitleAr($questionInArabic);
        $questionEntity->setStatus($evaluation->getStatus());
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

    public function updateQuestion($oldQuestionTitle, $newQuestionTitle,$oldQuestionTitleAr, $newQuestionTitleAr, $evaluation)
    {
        $evaluationId = $evaluation->getId();
        $question = $this->query->findOneBy("Courses\Entity\Question", array(
            'questionTitle' => $oldQuestionTitle,
            'questionTitleAr' => $oldQuestionTitleAr,
            'evaluation' => $evaluationId
        ));
        $question->setQuestionTitle($newQuestionTitle);
        $question->setQuestionTitleAr($newQuestionTitleAr);
        $question->setStatus($evaluation->getStatus());
        $this->query->save($question);
    }

    public function validateQuestion($questions, $key, $keyAr)
    {
        unset($questions['submit']);
        $messages = array();
        $stringValidator = new \Zend\Validator\Regex('/^a-zA-Z0-9 \?|\s/');
        $tempArray = array_merge($questions[$key], $questions[$keyAr]);
        foreach ($tempArray as $question) {
            // start question validation
            $isStringValid = $stringValidator->isValid($question);
            // check if string
            if (!$isStringValid) {
                array_push($messages, $question . " : is not a valid question ... please insert a valid one");
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

    /**
     * Send mail
     * 
     * @access private
     * @param string $userEmail
     * @param bool $editFlag
     * @throws \Exception From email is not set
     * @throws \Exception To email is not set
     */
    private function sendMail($userEmail, $editFlag)
    {
        $forceFlush = (APPLICATION_ENV == "production" ) ? false : true;
        $cachedSystemData = $this->systemCacheHandler->getCachedSystemData($forceFlush);
        $settings = $cachedSystemData[CacheHandler::SETTINGS_KEY];

        if (array_key_exists(Settings::SYSTEM_EMAIL, $settings)) {
            $from = $settings[Settings::SYSTEM_EMAIL];
        }
        if (array_key_exists(Settings::ADMIN_EMAIL, $settings)) {
            $to = $settings[Settings::ADMIN_EMAIL];
        }

        if (!isset($from)) {
            throw new \Exception("From email is not set");
        }
        if (!isset($to)) {
            throw new \Exception("To email is not set");
        }
        $templateParameters = array(
            "email" => $userEmail,
        );

        if ($editFlag === false) {
            $templateName = MailTempates::NEW_EVALUATION_NOTIFICATION_TEMPLATE;
            $subject = MailSubjects::NEW_EVALUATION_NOTIFICATION_SUBJECT;
        }
        else {
            $templateName = MailTempates::UPDATED_EVALUATION_NOTIFICATION_TEMPLATE;
            $subject = MailSubjects::UPDATED_EVALUATION_NOTIFICATION_SUBJECT;
        }

        $mailArray = array(
            'to' => $to,
            'from' => $from,
            'templateName' => $templateName,
            'templateParameters' => $templateParameters,
            'subject' => $subject,
        );
        $this->notification->notify($mailArray);
    }

}
