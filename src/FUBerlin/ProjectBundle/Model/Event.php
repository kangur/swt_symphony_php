<?php

namespace FUBerlin\ProjectBundle\Model;

use \FUBerlin\ProjectBundle\Model\om\BaseEvent;

class Event extends BaseEvent {

    public function getEventPositionsTotal() {
        $sum = 0;
        $positions = $this->getEventPositions();
        foreach ($positions as $position) {
            $sum+=$position->getAmount();
        }
        return $sum;
    }

    public function getEventPositionsTotalForUser(\FUBerlin\ProjectBundle\Model\User $user) {
        $sum = 0;
        $positions = $this->getEventPositions();
        foreach ($positions as $position) {
            if ($position->getUser() == $user) {
                $sum+=$position->getAmount();
            }
        }
        return $sum;
    }

    public function getMemberUsers($criteria = null, PropelPDO $con = null) {
        $memberUsers = parent::getMemberUsers($criteria, $con);
        if (!$memberUsers->contains($this->getOwnerUser())) {
            $memberUsers->prepend($this->getOwnerUser());
        }
        return $memberUsers;
    } 

    public function getAmountForUser(\FUBerlin\ProjectBundle\Model\User $user) {
        $totalPerUser = $this->getEventPositionsTotal()/count($this->getMemberUsers());
        $userTotal = $this->getEventPositionsTotalForUser($user);
        return round($userTotal-$totalPerUser,2);
    }

    public function isMember(\FUBerlin\ProjectBundle\Model\User $user = null) {
        return ($this->getOwnerUser() == $user) || (EventMemberQuery::create()->filterByEvent($this)->filterByMemberUser($user)->count() > 0);
    }
    
    public function  canBeDeletedByUser(\FUBerlin\ProjectBundle\Model\User $user) {
        return !$this->getBilled() && ($this->getOwnerUser() == $user);
    }

}
