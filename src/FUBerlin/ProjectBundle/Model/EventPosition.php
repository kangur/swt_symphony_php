<?php

namespace FUBerlin\ProjectBundle\Model;

use FUBerlin\ProjectBundle\Model\om\BaseEventPosition;

class EventPosition extends BaseEventPosition
{
    public function canBeDeletedByUser(\FUBerlin\ProjectBundle\Model\User $user = null) {
        if ($this->getEvent()->getBilled()){
            return false;
        } else {            
            return ($this->getEvent()->getOwnerUser() == $user) || ($this->getUser() == $user);
        }
    }    
<<<<<<< HEAD
=======
    
    
>>>>>>> 0eee9bef76bc2f4d30b6ec8436923347823fc327
}
