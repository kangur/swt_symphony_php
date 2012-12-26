<?php

namespace FUBerlin\ProjectBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use \FUBerlin\ProjectBundle\Model\User;

class UserController extends Controller {

    /**
     * @Route("/register", name="register")
     */
    public function registerAction(\Symfony\Component\HttpFoundation\Request $request) {
        $user = new User();
        $form = $this->createForm(new \FUBerlin\ProjectBundle\Form\Type\UserType(), $user);        
        if ($request->isMethod('POST')) {
            $form->bind($request);
            if ($form->isValid()) {
                /* @var $user \FUBerlin\ProjectBundle\Model\User */
                $user->save();

                return $this->redirect($this->generateUrl('register_success'));
            }
        }
        return $this->render(
                        'FUBerlinProjectBundle:User:register.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("/register/success", name="register_success")
     */
    public function registerSuccessAction(\Symfony\Component\HttpFoundation\Request $request) {
        return $this->render(
                        'FUBerlinProjectBundle:User:register_success.html.twig');
    }

}
