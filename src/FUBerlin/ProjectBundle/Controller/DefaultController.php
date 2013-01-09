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
        return array('name' => 'Ich bin'.$name);
    }
}
