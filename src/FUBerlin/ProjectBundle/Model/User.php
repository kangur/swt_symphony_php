<?php

namespace FUBerlin\ProjectBundle\Model;

use \FUBerlin\ProjectBundle\Model\om\BaseUser;
use Symfony\Component\Validator\Constraints as Assert;

class User extends BaseUser implements \Symfony\Component\Security\Core\User\UserInterface {

    public function getSalt() {
        return null;//'swt_2012_1234567890';
    }

    public function setPassword($v) {
        parent::setPassword(md5($v . $this->getSalt()));
        return $this;
    }

    public function getRoles() {
        return array('ROLE_USER');
    }

    public function eraseCredentials() {
        
    }

    /**
     * @Assert\True(message = "This username exists")
     */
    public function isValidUsername() {
        if ($this->isNew()) {
            return UserQuery::create()->filterByUserName($this->getUserName())->count() == 0;
        } else {
            return true;
        }
    }

    
    
    /**
     * @Assert\True(message = "There is an account for this email address")
     */
    public function isValidEmail() {
        if ($this->isNew()) {
            return UserQuery::create()->filterByEmail($this->getEmail())->count() == 0;
        } else {
            return true;
        }
    }
    
}
