<?php

namespace FUBerlin\ProjectBundle\Model;

use FUBerlin\ProjectBundle\Model\om\BaseEventComment;

class EventComment extends BaseEventComment {

    public function canBeDeletedByUser(\FUBerlin\ProjectBundle\Model\User $user = null) {
        return ($this->getEvent()->getOwnerUser() == $user);
    }

}
