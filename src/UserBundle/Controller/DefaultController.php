<?php

namespace UserBundle\Controller;

use AppBundle\Data\Model\User;
use AppBundle\Data\Repository\UserRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route(service="user.default_controller")
 */
class DefaultController extends BaseController
{
    private $_userRepository;

    function __construct(UserRepository $userRepository)
    {
        $this->_userRepository = $userRepository;

    }

    /**
     * @Route("/",name="user")
     * @Template("UserBundle:Default:index.html.twig")
     */
    public function indexAction()
    {
        $users = $this->_userRepository->GetUser();
        return array(
            'users' => $users
        );
    }

    /**
     * @Route("/login",name="user_login")
     * @Template("UserBundle:Default:login.html.twig")
     */
    public function LoginAction()
    {
        if ($this->GetSession()->get("email") != null && $this->GetSession()->get('usertype') == 'user')
            return $this->redirect('/user');
       
        return array();
    }

    /**
     * @Route("/ajax/login",name="user_ajax_login")
     */
    public function AjaxLoginAction(Request $request)
    {
        $email = $request->request->get('email');
        $password = $request->request->get('password');
        $user = $this->_userRepository->LoginUser($email, $password, 'user');
        if ($user != false) {

            $this->GetSession()->set('id', $user->ID);
            $this->GetSession()->set('email', $user->Email);
            $this->GetSession()->set('usertype', $user->UserType);
        }

        return new JsonResponse(array(
            'success' => !($user == false)
        ));

    }
    /**
     * @Route("/ajax/register",name="user_ajax_register")
     */
    public function AjaxRegisterAction(Request $request)
    {
        $data = $request->request->all();
        $user = New User();

        $user->Email        =$data["Email"];
        $user->Password     =$data["Password"];
        $user->UserType     ='user';
        $user->Name         =$data["Name"];
        $user->SurName      =$data["SurName"];
        $user->Address1     =$data["Address1"];
        $user->Address2     =$data["Address2"];
        $user->City         =$data["City"];
        $user->Country      ='Türkiye';
        $user->ZipCode      =$data["ZipCode"];
        $user->PhoneNumber  =$data["PhoneNumber"];
        $user->AccountType  =$data["AccountType"];
        $user->PaymentType  =$data["PaymentType"];
        $user->PaymentInfo  =$data["PaymentInfo"];
        $user->CreateIp     =$request->getClientIp();

        $register = $this->_userRepository->RegisterUser($user);

        return new JsonResponse(array(
            'success' => $register
        ));

    }

    /**
     * @Route("/logout",name="user_logout")
     */
    public function LogoutAction()
    {
        $this->GetSession()->clear();
        return $this->redirect('/user');

    }


    /**
     * @Route("/update",name="user_update")
     * @Template("UserBundle:Default:update.html.twig")
     */
    public function UpdateAction()
    {
        return array(
            'user' => $this->_userRepository->GetUserById($this->GetSession()->get('id'))
        );

    }

    /**
     * @Route("/ajax/update",name="user_ajax_update")
     */
    public function AjaxUpdateAction(Request $request)
    {
        $data = $request->request->all();
        $user = New User();

        $user->ID           =$this->GetSession()->get('id');
        $user->Email        =$data["Email"];
        $user->Password     =$data["Password"];
        $user->UserType     ='user';
        $user->Name         =$data["Name"];
        $user->SurName      =$data["SurName"];
        $user->Address1     =$data["Address1"];
        $user->Address2     =$data["Address2"];
        $user->City         =$data["City"];
        $user->Country      ='Türkiye';
        $user->ZipCode      =$data["ZipCode"];
        $user->PhoneNumber  =$data["PhoneNumber"];
        $user->PaymentType  =$data["PaymentType"];
        $user->PaymentInfo  =$data["PaymentInfo"];

        $register = $this->_userRepository->UpdateUserByID($user);

        return new JsonResponse(array(
            'success' => $register
        ));

    }
}