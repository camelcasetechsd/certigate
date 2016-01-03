<?php

namespace Users\Model;

use Users\Entity\User as UserEntity;
use Utilities\Service\Random;
use Zend\File\Transfer\Adapter\Http;
use DateTime;

/**
 * User Model
 * 
 * Handles User Entity related business
 * 
 * 
 * 
 * @property Utilities\Service\Query\Query $query
 * @property Utilities\Service\Random $random
 * 
 * @package users
 * @subpackage model
 */
class User {

    /**
     *
     * @var Utilities\Service\Query\Query 
     */
    protected $query;

    /**
     *
     * @var Utilities\Service\Random 
     */
    protected $random;

    /**
     * Set needed properties
     * 
     * 
     * @access public
     * @uses Random
     * 
     * @param Utilities\Service\Query\Query $query
     */
    public function __construct($query) {
        $this->query = $query;
        $this->random = new Random();
    }

    /**
     * Prepare users for display
     * 
     * 
     * @access public
     * @param array $data
     * @return array users array after being prepared for display
     */
    public function prepareForDisplay($data) {
        foreach ($data as $user) {
            switch ($user->status) {
                case UserEntity::STATUS_ACTIVE :
                    $user->status = 'Active';
                    $user->active = TRUE;
                    break;
                case UserEntity::STATUS_DELETED :
                    $user->status = 'Deleted';
                    break;
            }
        }
        return $data;
    }

    /**
     * Save User
     * 
     * 
     * @access public
     * @uses UserEntity
     * 
     * @param array $userInfo
     * @param Users\Entity\User $userObj ,default is null in case new user is being created
     */
    public function saveUser($userInfo, $userObj = null) {
        $em = $this->query->entityManager;

        if (is_null($userObj)) {
            $entity = new UserEntity();
        } else {
            $entity = $userObj;
        }

        $entity->username = $userInfo['username'];
        $entity->name = $userInfo['name'];
        if (is_null($userObj)) {
            $entity->password = UserEntity::hashPassword($userInfo['password']);
        }
        $dateString = $userInfo['dateOfBirth'];
        $date = new DateTime($dateString);
        $entity->dateOfBirth = $date;
        $entity->mobile = $userInfo['mobile'];
        $entity->description = $userInfo['description'];
        $entity->maritalStatus = $userInfo['maritalStatus'];

        $entity->role = $this->query->find('Users\Entity\Role', 1);

        if (!empty($userInfo['photo']['name'])) {
            $entity->photo = $this->savePhoto();
        }
        $entity->status = UserEntity::STATUS_ACTIVE;

        $em->persist($entity);

        $em->flush($entity);
    }

    /**
     * Save user photo
     * 
     * 
     * @access protected
     * @uses Http
     * 
     * @return string new attachment file name
     */
    protected function savePhoto() {
        $uploadResult = null;
        $upload = new Http();
        $imagesPath = 'public/upload/images/';
        $upload->setDestination($imagesPath);

        try {
            // upload received file(s)
            $upload->receive();
        } catch (\Exception $e) {
            $uploadResult = '/upload/images/defaultpic.png';
        }

        $name = $upload->getFileName('photo');
        $extention = pathinfo($name, PATHINFO_EXTENSION);
        //get random new name
        $newName = $this->random->getRandomUniqueName();

        rename($name, 'public/upload/images/' . $newName . '.' . $extention);

        $uploadResult = '/upload/images/' . $newName . '.' . $extention;
        return $uploadResult;
    }

    /**
     * Delete User
     * 
     * 
     * @access public
     * @param int $userId
     */
    public function deleteUser($userId) {
        $user = $this->query->find(/* $entityName = */ 'Users\Entity\User', $userId);
        $user->status = UserEntity::STATUS_DELETED;
        $this->query->entityManager->merge($user);
        $this->query->entityManager->flush($user);
    }

}
