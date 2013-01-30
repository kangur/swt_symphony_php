<?php

namespace FUBerlin\ProjectBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{
    /**
     * @Route("/")
     * @Template("FUBerlinProjectBundle:Default:index.html.twig")
     */
    public function indexAction($name = null)
    {
        $user = $this->get('security.context')->getToken()->getUser();
        $securityContext = $this->container->get('security.context');
        if ($securityContext->isGranted('ROLE_USER')){
            $events = \FUBerlin\ProjectBundle\Model\EventQuery::create()->distinct()->filterByMemberUser($user)->_or()->filterByOwnerUser($user)->limit(10)->find();
            return array('events' => $events);
        }
        
        return array('name' => 'Ich bin es '.$name, );
    }
}
