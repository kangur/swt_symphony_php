<?php

namespace FUBerlin\ProjectBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use \FUBerlin\ProjectBundle\Model\User;
use \FUBerlin\ProjectBundle\Model\Event;

class EventController extends Controller {

    /**
     * @Route("/event/add", name="event_add")
     */
    public function addEventAction(\Symfony\Component\HttpFoundation\Request $request) {
        $event = new Event();
        $form = $this->createForm(new \FUBerlin\ProjectBundle\Form\Type\EventType(), $event);
        if ($request->isMethod('POST')) {
            $form->bind($request);
            if ($form->isValid()) {
                /* @var $user \FUBerlin\ProjectBundle\Model\User */
                $user = $this->get('security.context')->getToken()->getUser();
                $event->setOwnerId($user->getId());
                $event->save();
                return $this->redirect($this->generateUrl('event_view', array('id' => $event->getId())));
            }
        }
        return $this->render(
                        'FUBerlinProjectBundle:Event:add.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("/event/view/{id}", name="event_view")
     */
    public function viewEventAction($id) {
        /* @var $event \FUBerlin\ProjectBundle\Model\Event */
        $event = \FUBerlin\ProjectBundle\Model\EventQuery::create()->findOneById($id);
        $user = $this->get('security.context')->getToken()->getUser();
        if (!$event) {
            throw $this->createNotFoundException('Event not found!');
        } else {
            $positionForm = $this->createForm(new \FUBerlin\ProjectBundle\Form\Type\EventPositionType);
            $positions = $event->getEventPositions();
            return $this->render(
                            'FUBerlinProjectBundle:Event:view.html.twig', array('event' => $event,
                        'position_form' => $positionForm->createView(),
                        'positions' => $positions,
                        'user_balance' => $event->getAmountForUser($user),
                        'members' => $event->getMemberUsers(),
                        'is_member' => $event->isMember($user)));
        }
    }

    /**
     * @Route("/event/join/{id}", name="event_join")
     */
    public function joinEventAction($id) {
        /* @var $event \FUBerlin\ProjectBundle\Model\Event */
        $event = \FUBerlin\ProjectBundle\Model\EventQuery::create()->findOneById($id);
        $user = $this->get('security.context')->getToken()->getUser();
        if (!$event) {
            throw $this->createNotFoundException('Event not found!');
        } else {
            $event->addMemberUser($user);
            $event->save();
            return $this->redirect($this->generateUrl('event_view', array('id' => $event->getId())));
        }
    }

    /**
     * @Route("/event/delete_position/{id}", name="position_delete")
     */
    public function deletePositionAction($id) {
        $position = \FUBerlin\ProjectBundle\Model\EventPositionQuery::create()->findOneById($id);
        $user = $this->get('security.context')->getToken()->getUser();
        if (!$position) {
            throw $this->createNotFoundException('Event not found!');
        } else {
            if ($position->canBeDeletedByUser($user)){
                $position->delete();
                return $this->redirect($this->generateUrl('event_view', array('id' => $position->getEvent()->getId())));
            }            
        }
    }

    /**
     * @Route("/event/add_position/{id}", name="position_add")
     */
    public function addPositionAction($id) {
        /* @var $event \FUBerlin\ProjectBundle\Model\Event */
        $event = \FUBerlin\ProjectBundle\Model\EventQuery::create()->findOneById($id);
        $user = $this->get('security.context')->getToken()->getUser();
        if (!$event) {
            throw $this->createNotFoundException('Event not found!');
        } else {
            if ($event->getBilled()) {
                throw new Exception('Event is already billed');
            }
            $request = $this->getRequest();
            if ($request->isMethod('POST')) {

                $eventPosition = new \FUBerlin\ProjectBundle\Model\EventPosition();
                $eventPosition->setEvent($event);
                $eventPosition->setUser($user);
                $form = $this->createForm(new \FUBerlin\ProjectBundle\Form\Type\EventPositionType(), $eventPosition);
                $form->bind($request);
                $eventPosition->save();
                return $this->redirect($this->generateUrl('event_view', array('id' => $id)));
            }
        }
    }

}
