<?php

namespace Organizations\Model;

use Organizations\Entity\Organization;
use Utilities\Service\DateNames;
use Utilities\Service\Status;
use Utilities\Service\Time;

/**
 * OrganizationMeta Model
 * 
 * Handles OrganizationUser Entity related business
 * 
 * 
 * @property Utilities\Service\Query\Query $query
 * 
 * @package organizations
 * @subpackage model
 */
class OrganizationMeta
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
     * Save OrganizationMeta
     * Add new role if user does not have it
     * 
     * @access public
     * @param Organizations\Entity\Organization $orgEntity
     * @param array $data ,default is empty array
     */
    public function saveOrganizationMeta($orgEntity, $data = array())
    {
        $types = array();
        if (!empty($data['type'])) {
            $types = $this->prepareTypes($data['type']);
        }
        // in case of edit
        else {
            $typesArray = $this->query->findBy('Organizations\Entity\OrganizationMeta', array(
                'organization' => $orgEntity->getId()
            ));
            foreach ($typesArray as $type) {
                array_push($types, $type->getType()->getId());
            }
        }
        
        foreach ($types as $type) {
            $orgMeta = new \Organizations\Entity\OrganizationMeta();
            $orgMeta->setOrganization($orgEntity);
            $orgMeta = $this->prepareDateValidation($orgMeta, $data, $type);
            $this->query->setEntity('Organizations\Entity\OrganizationMeta')->save($orgMeta);
        }
    }

    private function prepareTypes($path)
    {
        return explode('/', $path);
    }

    /**
     * function to set validation flag and convert date string into object
     * then set them to the $orgMeta object then return it back
     * @param Organizations\Entity\OrganizationMeta $orgMeta
     * @param array $data
     * @param int $type  // organization type id
     */
    private function prepareDateValidation($orgMeta, $data, $type)
    {
        // setting organization type
        $typeObj = $this->query->findOneBy('Organizations\Entity\OrganizationType', array(
            'id' => (int) $type
        ));
        $orgMeta->setType($typeObj);

        if ((int) $type == Organization::TYPE_ATC || (int) $type == Organization::TYPE_ATP) {
            //finding which Expiration date needed  
            $dateName = $this->getDateName((int) $type);
            $dateValidation = $this->validateDate($data[$dateName]);
            $orgMeta->setExpirationFlag($dateValidation);
            $orgMeta->setExpirationDate($data[$dateName]);
        }
        else {
            // if type 3 or 4 they can not expire (has no expiration date)
            $orgMeta->setExpirationFlag(Status::STATUS_NOT_YET_EXPIRED);
            $orgMeta->setExpirationDate(null);
        }
        return $orgMeta;
    }

    /**
     * returns date name
     * @param intger $type
     * @return string $dateName
     */
    private function getDateName($type)
    {
        switch ((int) $type) {
            case Organization::TYPE_ATC :
                $dateName = DateNames::ATC_EXPIRATION_DATE;
                break;
            case Organization::TYPE_ATP :
                $dateName = DateNames::ATP_EXPIRATION_DATE;
                break;
        }
        return $dateName;
    }

    /**
     * function to return if the date is expired or not
     * @param type $date
     */
    private function validateDate($date)
    {
        $timeNow = new \DateTime('now');

        if ($timeNow > $date) {
            return Status::STATUS_EXPIRED;
        }
        return Status::STATUS_NOT_YET_EXPIRED;
    }

}
