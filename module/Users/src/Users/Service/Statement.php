<?php

namespace Users\Service;

use Users\Entity\Role;

/**
 * Statement
 * 
 * Hold Statement related constants
 * 
 * @property array $rolesStatements
 * @property array $privacyStatement
 * 
 * @package users
 * @subpackage service
 */
class Statement
{

    /**
     * Agree statement
     */
    const STATEMENT_AGREE = 1;

    /**
     * Agree statement text
     */
    const STATEMENT_AGREE_TEXT = "Agreed";

    /**
     * Disagree statement
     */
    const STATEMENT_DISAGREE = 0;

    /**
     * Disagree statement text
     */
    const STATEMENT_DISAGREE_TEXT = "Disagreed";

    /**
     * Privacy statement type
     */
    const STATEMENT_PRIVACY_TYPE = "privacyStatement";

    /**
     * Student statement type
     */
    const STATEMENT_STUDENT_TYPE = "studentStatement";

    /**
     * Proctor statement type
     */
    const STATEMENT_PROCTOR_TYPE = "proctorStatement";

    /**
     * Instructor statement type
     */
    const STATEMENT_INSTRUCTOR_TYPE = "instructorStatement";

    /**
     * TestCenterAdministratorStatement statement type
     */
    const STATEMENT_TEST_CENTER_ADMINISTRATOR_TYPE = "testCenterAdministratorStatement";

    /**
     * TrainingManager statement type
     */
    const STATEMENT_TRAINING_MANAGER_TYPE = "trainingManagerStatement";

    /**
     * Privacy statement title
     */
    const STATEMENT_PRIVACY_TITLE = "Privacy Statement";

    /**
     * Student statement title
     */
    const STATEMENT_STUDENT_TITLE = "Student Statement";

    /**
     * Proctor statement title
     */
    const STATEMENT_PROCTOR_TITLE = "Proctor Statement";

    /**
     * Instructor statement title
     */
    const STATEMENT_INSTRUCTOR_TITLE = "Instructor Statement";

    /**
     * TestCenterAdministratorStatement statement title
     */
    const STATEMENT_TEST_CENTER_ADMINISTRATOR_TITLE = "Test Center Administrator Statement";

    /**
     * TrainingManager statement title
     */
    const STATEMENT_TRAINING_MANAGER_TITLE = "Training Manager Statement";

    /**
     * Statement role
     */
    const STATEMENT_ROLE = "role";

    /**
     * Statement type
     */
    const STATEMENT_TYPE = "type";

    /**
     * Statement title
     */
    const STATEMENT_TITLE = "title";

    /**
     * Statement content
     */
    const STATEMENT_CONTENT = "content";

    /**
     * Statement sentence
     */
    const STATEMENT_SENTENCE = "sentence";

    /**
     * Statement sentence
     */
    const STATEMENT_SENTENCE_PLACEHOLDER = 'I agree to <a href="javascript:void(0)">%s</a>';

    /**
     *
     * @var array
     */
    public $privacyStatement = array(
        self::STATEMENT_CONTENT => 'dummy Privacy Statement content',
        self::STATEMENT_TYPE => self::STATEMENT_PRIVACY_TYPE,
        self::STATEMENT_TITLE => self::STATEMENT_PRIVACY_TITLE,
        self::STATEMENT_ROLE => ''
    );

    /**
     *
     * @var array
     */
    public $rolesStatements = array(
        array(
            self::STATEMENT_CONTENT => 'dummy Proctor Statement content',
            self::STATEMENT_TYPE => self::STATEMENT_PROCTOR_TYPE,
            self::STATEMENT_TITLE => self::STATEMENT_PROCTOR_TITLE,
            self::STATEMENT_ROLE => Role::PROCTOR_ROLE
        ),
        array(
            self::STATEMENT_CONTENT => 'dummy Student Statement content',
            self::STATEMENT_TYPE => self::STATEMENT_STUDENT_TYPE,
            self::STATEMENT_TITLE => self::STATEMENT_STUDENT_TITLE,
            self::STATEMENT_ROLE => Role::STUDENT_ROLE
        ),
        array(
            self::STATEMENT_CONTENT => 'dummy Instructor Statement content',
            self::STATEMENT_TYPE => self::STATEMENT_INSTRUCTOR_TYPE,
            self::STATEMENT_TITLE => self::STATEMENT_INSTRUCTOR_TITLE,
            self::STATEMENT_ROLE => Role::INSTRUCTOR_ROLE
        ),
        array(
            self::STATEMENT_CONTENT => 'dummy Test Center Administrator Statement content',
            self::STATEMENT_TYPE => self::STATEMENT_TEST_CENTER_ADMINISTRATOR_TYPE,
            self::STATEMENT_TITLE => self::STATEMENT_TEST_CENTER_ADMINISTRATOR_TITLE,
            self::STATEMENT_ROLE => Role::TEST_CENTER_ADMIN_ROLE
        ),
        array(
            self::STATEMENT_CONTENT => 'dummy Training Manager Statement content',
            self::STATEMENT_TYPE => self::STATEMENT_TRAINING_MANAGER_TYPE,
            self::STATEMENT_TITLE => self::STATEMENT_TRAINING_MANAGER_TITLE,
            self::STATEMENT_ROLE => Role::TRAINING_MANAGER_ROLE
        ),
    );

    /**
     * Prepare statements data
     * 
     * @access public
     */
    public function __construct()
    {
        foreach ($this->rolesStatements as $statementKey => $statementArray) {
            $title = $statementArray[self::STATEMENT_TITLE];
            $this->rolesStatements[$statementKey][self::STATEMENT_SENTENCE] = sprintf(self::STATEMENT_SENTENCE_PLACEHOLDER, $title);
        }
        $title = $this->privacyStatement[self::STATEMENT_TITLE];
        $this->privacyStatement[self::STATEMENT_SENTENCE] = sprintf(self::STATEMENT_SENTENCE_PLACEHOLDER, $title);
    }

}
