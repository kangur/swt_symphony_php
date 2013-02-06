<?php

namespace FUBerlin\ProjectBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;

class SecurityController extends Controller {

    public function setContainer(\Symfony\Component\DependencyInjection\ContainerInterface $container = NULL)
    {
        parent::setContainer($container);
        $request = parent::getRequest();
        $request->setLocale($this->get('session')->get('_locale'));
    }
    
    /**
     * 
     * @Route("/login", name="login")
     * @Route("/login_check", name="login_check")
     * @Route("/logout", name="logout")
     */
    public function loginAction() {
        $request = $this->getRequest();
        $session = $request->getSession();

        // get the login error if there is one
        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(
                    SecurityContext::AUTHENTICATION_ERROR
            );
        } else {
            $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
            $session->remove(SecurityContext::AUTHENTICATION_ERROR);
        }
//        echo '<pre>';
//        print_r($error);
        return $this->render(
                        'FUBerlinProjectBundle:Security:login.html.twig', array(
                    // last username entered by the user
                    'last_username' => $session->get(SecurityContext::LAST_USERNAME),
                    'error' => $error,
                        )
        );
    }

}
