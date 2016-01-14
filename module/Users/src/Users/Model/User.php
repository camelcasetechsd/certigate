<?php

namespace Users\Model;

use Users\Entity\User as UserEntity;
use Utilities\Service\Random;
use Zend\File\Transfer\Adapter\Http;
use Users\Entity\Role;
use Utilities\Service\Status;

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
    public function __construct($query)
    {
        $this->query = $query;
        $this->random = new Random();
    }

    /**
     * Save User
     * 
     * 
     * @access public
     * @uses UserEntity
     * 
     * @param array $userInfo
     * @param UserEntity $userObj ,default is null in case new user is being created
     */
    public function saveUser($userInfo, $userObj = null) {
        if (is_null($userObj)) {
            $userObj = new UserEntity();
        } 
        if (! empty($userInfo['password'])) {
            $userInfo['password'] = UserEntity::hashPassword($userInfo['password']);
        }
        if (!empty($userInfo['photo']['name'])) {
            $userInfo['photo'] = $this->savePhoto();
        }
        $userInfo['status'] = Status::STATUS_ACTIVE;

        // All users should always have user role
        $userRole = $this->query->findOneBy("Users\Entity\Role", /*$criteria =*/ array("name" => Role::USER_ROLE));
        if(!in_array($userRole->getId(), $userInfo['roles'])){
            $userInfo['roles'][] = $userRole->getId();
        }
        
        $this->query->setEntity("Users\Entity\User")->save($userObj, $userInfo);
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
        $user->status = Status::STATUS_DELETED;
        $this->query->entityManager->merge($user);
        $this->query->entityManager->flush($user);
    }

    }
