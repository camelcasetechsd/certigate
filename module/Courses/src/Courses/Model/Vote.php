<?php

namespace Courses\Model;

/**
 * this model meant for course evaluation votes 
 */
class Vote
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
     * Save course votes
     * 
     * @access public
     * 
     * @param array $questionIds
     * @param array $values
     * @param Users\Entity\User $userObj
     * @param Courses\Entity\Evaluation $evalObj
     * @param Courses\Entity\CourseEvent $courseEvent
     */
    public function saveCourseVotes($questionIds, $values, $userObj, $evalObj, $courseEvent)
    {
        // removing unset value form array
        unset($values['submit']);
        //looping over values
        for ($i = 0; $i < count($questionIds); $i++) {
            $vote = new \Courses\Entity\Vote();
            $questionObj = $this->query->findOneBy('Courses\Entity\Question', array(
                'id' => $questionIds[$i]
            ));
            $vote->setCourseEvent($courseEvent);
            $vote->setEvaluation($evalObj);
            $vote->setUser($userObj);
            $vote->setQuestion($questionObj);
            // each key is radios_XX  where x is question Id
            $vote->setVote($values['radios_' . $questionIds[$i]]);
            // save vote
            $this->query->setEntity('Courses\Entity\Vote')->save($vote);
            // update course evaluation percentage
            $evalObj->setPercentage($this->getVotePercentage($evalObj));
            $this->query->setEntity('Courses\Entity\Evaluation')->save($evalObj);
        }
    }

    /**
     * Get votes average for course event
     * 
     * @access public
     * @param int $courseEventId
     * @return array
     */
    public function getVotesAverage($courseEventId)
    {
        $votesArray = $this->query->setEntity('Courses\Entity\Vote')->entityRepository->getVotesByCourseEvent($courseEventId);

        $processedVotes = array();
        // process votes total and count
        foreach($votesArray as $voteArray){
            if(!array_key_exists($voteArray["id"], $processedVotes)){
                $processedVotes[$voteArray["id"]]["questionTitle"] = $voteArray["questionTitle"];
                $processedVotes[$voteArray["id"]]["questionTitleAr"] = $voteArray["questionTitleAr"];
                $processedVotes[$voteArray["id"]]["votesTotal"] = $voteArray["vote"];
                $processedVotes[$voteArray["id"]]["votesCount"] = 1;
            }else{
                $processedVotes[$voteArray["id"]]["votesTotal"] += $voteArray["vote"];
                $processedVotes[$voteArray["id"]]["votesCount"]++;
            }
        }
        // calculate votes average
        foreach($processedVotes as &$processedVote){
            $processedVote["votesAverage"] = number_format($processedVote["votesTotal"] / $processedVote["votesCount"] ,2);
            $processedVote["votesPercent"] = round(100 * $processedVote["votesAverage"] / 5 ,2);
        }
        return array_values($processedVotes);
    }

    /**
     * 
     * @param Evaluation $evalObj
     */
    private function getVotePercentage($evalObj)
    {

        $questionCount = count($evalObj->getQuestions());
        $votes = $evalObj->getVotes();
        $currentVotes = array();
        $usersVoted = array();

        foreach ($votes as $vote) {
            array_push($currentVotes, $vote->getVote());
            array_push($usersVoted, $vote->getUser()->getId());
        }
        $sum = array_sum($currentVotes);
        $users = count(array_unique($usersVoted));
        
        return (($sum / ($questionCount * 5)) * 100) / $users;
    }

}
