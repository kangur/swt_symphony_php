<?php

namespace FUBerlin\ProjectBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use \FUBerlin\ProjectBundle\Model\User;
use \FUBerlin\ProjectBundle\Model\Event;

class EventController extends Controller {

    public function getRequest() {
        $request = parent::getRequest();
        $request->setLocale($this->get('session')->get('_locale'));
        return $request;
    }

    private function showError($errorMessage) {
        return $this->render(
                        'FUBerlinProjectBundle:Event:error.html.twig', array('errorMessage' => $errorMessage));
    }

    private function showSuccess($successMessage) {
        return $this->render(
                        'FUBerlinProjectBundle:Event:success.html.twig', array('successMessage' => $successMessage));
    }

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
     * @Route("/my_billed_positions", name="event_billed_positions_view")
     */
    public function viewBilledPositionsAction() {
        $user = $this->get('security.context')->getToken()->getUser();
        $securityContext = $this->container->get('security.context');
        if ($securityContext->isGranted('ROLE_USER')) {
            $positions = \FUBerlin\ProjectBundle\Model\EventBillingPositionQuery::create()->filterByUser($user)->find();
            return $this->render(
                            'FUBerlinProjectBundle:Event:billedpositions.html.twig', array('positions' => $positions));
        } else {
            return $this->showError('You must log in to see your billed events');
        }
    }

    /**
     * @Route("/position/pay/{id}", name="event_mark_position_as_paid")
     */
    public function markPositionAsPaidAction($id) {
        $user = $this->get('security.context')->getToken()->getUser();
        $position = \FUBerlin\ProjectBundle\Model\EventBillingPositionQuery::create()->findOneById($id);
        /* @var $position \FUBerlin\ProjectBundle\Model\EventBillingPosition */
        if (!$position) {
            return $this->showError('Position not found');
        } else if ($position->getUser() != $user) {
            return $this->showError('Position does not belong to you');
        } else {
            $position->setPaid(true);
            $position->save();
            return $this->redirect($this->generateUrl('event_billed_positions_view'));
        }
        return $this->render(
                        'FUBerlinProjectBundle:Event:billedpositions.html.twig', array('positions' => $positions));
    }

    /**
     * @Route("/event/view/{id}", name="event_view")
     */
    public function viewEventAction($id) {
        /* @var $event \FUBerlin\ProjectBundle\Model\Event */
        $event = \FUBerlin\ProjectBundle\Model\EventQuery::create()->findOneById($id);
        $user = $this->get('security.context')->getToken()->getUser();
        if (!$event) {
            return $this->showError('Event not found!');
        } else {
            $positionForm = $this->createForm(new \FUBerlin\ProjectBundle\Form\Type\EventPositionType);

            $commentForm = $this->createForm(new \FUBerlin\ProjectBundle\Form\Type\EventCommentType);

            $positions = $event->getEventPositions();
            $comments = $event->getEventComments();

            $securityContext = $this->container->get('security.context');
            if ($securityContext->isGranted('ROLE_USER')) {
                return $this->render(
                                'FUBerlinProjectBundle:Event:view.html.twig', array('event' => $event,
                            'position_form' => $positionForm->createView(),
                            'positions' => $positions,
                            'comment_form' => $commentForm->createView(),
                            'comments' => $comments,
                            'user_balance' => $event->getAmountForUser($user),
                            'members' => $event->getMemberUsers(),
                            'is_member' => $event->isMember($user)));
            } else {
                return $this->render(
                                'FUBerlinProjectBundle:Event:view.html.twig', array('event' => $event,
                            'positions' => $positions,
                            'members' => $event->getMemberUsers(),
                            'is_member' => false));
            }
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
            return $this->showError('Event not found!');
            //throw $this->createNotFoundException('Event not found!');
        } else if (\FUBerlin\ProjectBundle\Model\EventMemberQuery::create()->filterByMemberUser($user)->filterByEvent($event)->count() > 0) {
            return $this->showError('You are already a member of this event');
        } else {
            $event->addMemberUser($user);
            $event->save();
            return $this->redirect($this->generateUrl('event_view', array('id' => $event->getId())));
        }
    }

    /**
     * @Route("/event/bill/{id}", name="event_bill")
     */
    public function billEventAction($id) {
        /* @var $event \FUBerlin\ProjectBundle\Model\Event */

        $user = $this->get('security.context')->getToken()->getUser();

        $event = \FUBerlin\ProjectBundle\Model\EventQuery::create()->filterByOwnerUser($user)->findOneById($id);
        if (!$event) {
            return $this->showError('Event not found!');
        } else {
            if ($event->getBilled()) {
                return $this->showError('Event is already billed');
            }
            $event->setBilled(true);
            $event->save();
            $eventMembers = $event->getMemberUsers();

            foreach ($eventMembers as $member) {
                $billingPosition = new \FUBerlin\ProjectBundle\Model\EventBillingPosition;
                $billingPosition->setUser($member);
                $billingPosition->setEvent($event);
                $billingPosition->setAmount($event->getAmountForUser($member));
                $billingPosition->save();
            }



            return $this->redirect($this->generateUrl('event_view', array('id' => $event->getId())));
        }
    }

    /**
     * @Route("/event/delete_event/{id}", name="event_delete")
     */
    public function deleteEventAction($id) {
        $event = \FUBerlin\ProjectBundle\Model\EventQuery::create()->findOneById($id);
        $user = $this->get('security.context')->getToken()->getUser();
        if (!$event) {
            return $this->showError('Event not found!');
        } else {
            if ($event->canBeDeletedByUser($user)) { //
                $event->delete();
                return $this->showSuccess('Event has been deleted!');
            } else {
                return $this->showError('Event cannot be deleted!');
            }
        }
    }

    /**
     * @Route("/event/delete_position/{id}", name="position_delete")
     */
    public function deletePositionAction($id) {
        $position = \FUBerlin\ProjectBundle\Model\EventPositionQuery::create()->findOneById($id);
        $user = $this->get('security.context')->getToken()->getUser();
        if (!$position) {
            return $this->showError('Event not found!');
        } else {
            if ($position->canBeDeletedByUser($user)) { //
                $position->delete();
                return $this->redirect($this->generateUrl('event_view', array('id' => $position->getEvent()->getId())));
            } else {
                return $this->showError('Bullshit');
            }
        }
    }

    /**
     * @Route("/event/delete_comment/{id}", name="comment_delete")
     */
    public function deleteCommentAction($id) {
        $comment = \FUBerlin\ProjectBundle\Model\EventCommentQuery::create()->findOneById($id);
        $user = $this->get('security.context')->getToken()->getUser();
        /* @var $comment \FUBerlin\ProjectBundle\Model\EventComment */
        if (!$comment) {
            return $this->showError('Event not found!');
        } else {
            if ($comment->canBeDeletedByUser($user)) {
                $comment->delete();
                return $this->redirect($this->generateUrl('event_view', array('id' => $comment->getEvent()->getId())));
            } else {
                return $this->showError('You can\'t delete this comment!');
            }
        }
    }

    /**
     * @Route("/event/add_comment/{id}", name="comment_add")
     */
    public function addCommentAction($id) {
        /* @var $event \FUBerlin\ProjectBundle\Model\Event */
        $event = \FUBerlin\ProjectBundle\Model\EventQuery::create()->findOneById($id);
        $user = $this->get('security.context')->getToken()->getUser();
        if (!$event) {
            return $this->showError('Event not found!');
        } else {
            if (!$event->isMember($user)) {
                return $this->showError('You are not a member of this event!');
            }
            $request = $this->getRequest();
            if ($request->isMethod('POST')) {

                $eventComment = new \FUBerlin\ProjectBundle\Model\EventComment();
                $eventComment->setEvent($event);
                $eventComment->setUser($user);
                $eventComment->setTimestamp(time());

                $form = $this->createForm(new \FUBerlin\ProjectBundle\Form\Type\EventCommentType(), $eventComment);
                $form->bind($request);
                $eventComment->save();
                return $this->redirect($this->generateUrl('event_view', array('id' => $id)));
            }
        }
    }

    /**
     * @Route("/event/edit_position/{id}", name="position_edit")
     */
    /*
      public function editPositionAction($id) {
      $position = \FUBerlin\ProjectBundle\Model\EventPositionQuery::create()->findOneById($id);
      $user = $this->get('security.context')->getToken()->getUser();
      if (!$position) {
      return $this->showError('Event not found!');
      } else {
      if ($position->canBeEditByUser($user)) {
     * 
     * 
      $position->save();
      return $this->redirect($this->generateUrl('event_view', array('id' => $id)));
      }
      }
      } */

    /**
     * @Route("/event/add_position/{id}", name="position_add")
     */
    public function addPositionAction($id) {
        /* @var $event \FUBerlin\ProjectBundle\Model\Event */
        $event = \FUBerlin\ProjectBundle\Model\EventQuery::create()->findOneById($id);
        $user = $this->get('security.context')->getToken()->getUser();
        if (!$event) {
            return $this->showError('Event not found!');
        } else {
            if ($event->getBilled()) {
                return $this->showError('Event is already billed!');
            }
            if (!$event->isMember($user)) {
                return $this->showError('You are not a member of this event!');
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
