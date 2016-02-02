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

    public function saveCourseVotes($questionIds, $values, $userObj, $evalObj)
    {
        // removing unset value form array
        unset($values['submit']);
        //looping over values
        for ($i = 0; $i < count($questionIds); $i++) {
            $vote = new \Courses\Entity\Vote();
            $questionObj = $this->query->findOneBy('Courses\Entity\Question', array(
                'id' => $questionIds[$i]
            ));
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
