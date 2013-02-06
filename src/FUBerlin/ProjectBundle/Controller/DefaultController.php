<?php

namespace FUBerlin\ProjectBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller {

    public function setContainer(\Symfony\Component\DependencyInjection\ContainerInterface $container = NULL) {
        parent::setContainer($container);
        $request = parent::getRequest();
        $request->setLocale($this->get('session')->get('_locale'));
    }

    private function getStats(){
        $stats=array();
        $stats['userCount'] = \FUBerlin\ProjectBundle\Model\UserQuery::create()->count();
        $stats['eventCount'] = \FUBerlin\ProjectBundle\Model\EventQuery::create()->count();
        $stats['billedEventCount'] = \FUBerlin\ProjectBundle\Model\EventQuery::create()->filterByBilled(true)->count();
        $stats['commentCount'] = \FUBerlin\ProjectBundle\Model\EventCommentQuery::create()->count();
        $stats['positionSum'] = \FUBerlin\ProjectBundle\Model\EventPositionQuery::create()->withColumn('SUM(event_position.amount)','sum')->select('sum')->findOne();
        return $stats;
    }

        
    /**
     * @Route("/", name="index")
     * @Template("FUBerlinProjectBundle:Default:index.html.twig")
     */
    public function indexAction($name = null) {
        $this->getStats();
        $this->getRequest()->getLocale();
        $user = $this->get('security.context')->getToken()->getUser();
        $securityContext = $this->container->get('security.context');
        if ($securityContext->isGranted('ROLE_USER')) {
            $events = \FUBerlin\ProjectBundle\Model\EventQuery::create()->distinct()->leftJoinEventMember()->filterByMemberUser($user)->find();
            $myEvents = \FUBerlin\ProjectBundle\Model\EventQuery::create()->filterByOwnerUser($user)->find();

            return array('events' => $events,
                'stats' => $this->getStats(),
                'myEvents' => $myEvents);
        }

        return array('name' => 'Ich bin es ' . $name, 'stats' => $this->getStats());
    }

    /**
     * @Route("/language/{locale}", name="locale")
     */
    public function changeLanguageAction($locale) {
        $this->get('session')->set('_locale', $locale);
        $request = $this->getRequest();
        $referer = $request->headers->get('referer');
        if ($referer) {
            return new \Symfony\Component\HttpFoundation\RedirectResponse($referer);
        } else {
            return $this->redirect($this->generateUrl('index'));
        }
    }

}
