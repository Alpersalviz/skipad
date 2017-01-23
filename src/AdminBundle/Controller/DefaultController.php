<?php

namespace AdminBundle\Controller;

use AppBundle\Data\Repository\UserRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route(service="admin.default_controller")
 */
class DefaultController extends BaseController
{
     private $_userRepository;

    function __construct(UserRepository $userRepository)
    {
        $this->_userRepository = $userRepository;

    }
    /**
     * @Route("/",name="admin")
     * @Template("AdminBundle:Default:index.html.twig")
     */
    public function indexAction()
    {
        $users = $this->_userRepository->GetUser();
        return array(
            'users' => $users
        );
    }

    /**
     * @Route("/login",name="admin_login")
     * @Template("AdminBundle:Default:login.html.twig")
    */
    public function LoginAction()
    {
        return array();
    }

    /**
     * @Route("/ajax/login",name="admin_ajax_login")
     */
    public function AjaxLoginAction(Request $request)
    {
        $email = $request->request->get('email');
        $password =  $request->request->get('password');
        $user = $this->_userRepository->LoginUser($email,$password,'admin');
        if ($user != false) {

            $this->GetSession()->set('id',$user->ID);
            $this->GetSession()->set('email',$user->Email);
            $this->GetSession()->set('usertype',$user->UserType);
        }


        return new JsonResponse(array(
            'success' => !($user == false)
        ));

    }
    /**
     * @Route("/logout",name="logout")
     */
    public function LogoutAction()
    {
        $this->GetSession()->clear();
        return $this->redirect('/admin');

    }
}
