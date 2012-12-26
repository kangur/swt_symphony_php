<?php

namespace FUBerlin\ProjectBundle\Model;

use FUBerlin\ProjectBundle\Model\om\BaseUserQuery;

class UserQuery extends BaseUserQuery implements \Symfony\Component\Security\Core\User\UserProviderInterface {

    public function loadUserByUsername($username) {        
        $user = self::create()->filterByUserName($username)->findOne();
        
        if ($user){            
            return $user; 
            
        } else {
            throw new UsernameNotFoundException(sprintf('Unable to find user "%s".', $username), null, 0);
        }
        
        return $user;
    }

    public function refreshUser(\Symfony\Component\Security\Core\User\UserInterface $user) {
        $class = get_class($user);
        if (!$this->supportsClass($class)) {
            throw new \Symfony\Component\Security\Core\Exception\UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $class));
        }
        return $this->loadUserByUsername($user->getUsername());
        //return self::create()->findOneById($user->getId());
    }

    public function supportsClass($class) {
        return $class  === 'FUBerlin\ProjectBundle\Model\User';
    }

}
