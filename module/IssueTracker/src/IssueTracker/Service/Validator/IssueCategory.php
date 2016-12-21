<?php

namespace IssueTracker\Service\Validator;

use Utilities\Service\Validator\UniqueObject as UtilitiesUniqueObject;

class IssueCategory extends UtilitiesUniqueObject
{

    const ERROR_PARENT_CATEGORY_EXISTED = "parentCategoryExisted";

    /**
     * @var array Message templates
     */
    protected $messageTemplates = array(
        self::ERROR_PARENT_CATEGORY_EXISTED => "This parent category '%value%' is already existed !",
        self::ERROR_OBJECT_NOT_UNIQUE       => "There is already another object matching '%value%'",
    );

    /**
     * Specific validator for Issue Category
     *
     * @access public
     * @param  mixed $value
     * @param  array $context ,default is null
     * @return boolean is valid or not
     */
    public function isValid($value, $context = null)
    {
        if (empty($context['parent'])) {
            $result = $this->objectRepository->findBy([
                'parent' => null,
                'title'  => $value
            ]);
            if ($result && count($result) > 0) {
                $this->error(self::ERROR_PARENT_CATEGORY_EXISTED, $value);
                return false;
            }
        } else {
            return parent::isValid($value, $context);
        }
    }

}
