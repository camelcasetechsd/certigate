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
            $this->query->save($vote);
        }
    }

}
