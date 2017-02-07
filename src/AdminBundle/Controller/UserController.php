<?php
/**
 * Created by PhpStorm.
 * User: AlperSalviz
 * Date: 6.2.2017
 * Time: 13:36
 */

namespace AdminBundle\Controller;


use AppBundle\Data\Model\User;
use AppBundle\Data\Repository\UserRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route(service="admin.user_controller")
 */
class UserController extends BaseController
{

    private $_userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->_userRepository = $userRepository;

    }

    /**
     * @Route("/user/list",name="user_list")
     * @Template("AdminBundle:User:list.html.twig")
     */
    public function ListUserAction(){

        return array(
            'users' => $this->_userRepository->GetUserByType()
        );

    }

    /**
     * @Route("/user/ajax/cancel",name="admin_user_cancel")
     */
    public function AjaxCancelUserAction(Request $request){

        $data = $request->request->all();
        $id = $data["id"];
        $publish = $data["publish"];

        $cancel = $this->_userRepository->UpdateUserPublish($id,$publish);


        return new JsonResponse(array(
            'success' => $cancel,
            'message' => "Güncellendi"
        ));

    }
    /**
     * @Route("/user/update/{id}",name="admin_user_update")
     * @Template("AdminBundle:User:update.html.twig")
     */
    public function UpdateUserAction($id)
    {
        return array(
            'user' => $this->_userRepository->GetUserById($id)
        );

    }


    /**
     * @Route("/user/ajax/update",name="admin_user_ajax_update")
     */
    public function AjaxUpdateUserAction(Request $request)
    {
        $data = $request->request->all();
        $user = New User();

        $user->ID           =$data["UserId"];
        $user->Email        =$data["Email"];
        $user->Password     =$data["Password"];
        $user->UserType     ='user';
        $user->Name         =$data["Name"];
        $user->SurName      =$data["SurName"];
        $user->Address1     =$data["Address1"];
        $user->Address2     =$data["Address2"];
        $user->Balance      =(float)$data["Balance"];
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