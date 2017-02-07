<?php
/**
 * Created by PhpStorm.
 * User: AlperSalviz
 * Date: 1.2.2017
 * Time: 22:04
 */

namespace UserBundle\Controller;
use AppBundle\Data\Model\Payment;
use AppBundle\Data\Repository\PaymentRepository;
use AppBundle\Data\Repository\SettingsRepository;
use AppBundle\Data\Repository\UserRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route(service="user.payment_controller")
 */

class PaymentController extends BaseController
{

    private $_userRepository;

    private $_paymentRepository;

    private  $_setttingsRepository;

    function __construct(UserRepository $userRepository , PaymentRepository $paymentRepository ,SettingsRepository $settingsRepository)
    {
        $this->_userRepository = $userRepository;
        $this->_paymentRepository = $paymentRepository;
        $this->_setttingsRepository = $settingsRepository;

    }

    /**
     * @Route("/get/payment",name="user_get_payment")
     * @Template("UserBundle:Payment:get_payment.html.twig")
     */
    public function GetPaymentAction()
    {
        $id= $this->GetSession()->get("id");
        $users = $this->_userRepository->GetUserById($id);

        $setting = $this->_setttingsRepository->GetSetting();
        return array(
            'user' => $users,
            'setting'=> $setting
        );
    }

    /**
     * @Route("/list/payment/{type}",name="user_list_payment")
     * @Template("UserBundle:Payment:list.html.twig")
     */
    public function ListPaymentAction($type)
    {
        
        $id= $this->GetSession()->get("id");
        $payments = $this->_paymentRepository->GetPayments($type,$id);
        return array(
            'payments' => $payments,
            'type' => $type
        );
    }
    /**
     * @Route("/ajax/get/payment",name="user_ajax_get_payment")
     */
    public function AjaxGetPaymentAction(Request $request)
    {
        $payment = New Payment();
        $id= $this->GetSession()->get("id");
        $user = $this->_userRepository->GetUserById($id);
        $setting = $this->_setttingsRepository->GetSetting();

        if($setting->MinimumPayment > $user->Balance){

            return new JsonResponse(array(
                'success' => false
            ));

        }

        $payment->UserId        = $id;
        $payment->Balance       = $user->Balance;
        $payment->PaymentInfo   = $user->PaymentInfo;
        $payment->PaymentType   = $user->PaymentType;
        $payment->Status        = "wait";
        $payment->Type          = "send";

        $getPayment = $this->_paymentRepository->AddPayment($payment);

        return new JsonResponse(array(
            'success' => $getPayment
        ));

    }
    /**
     * @Route("/set/payment",name="user_set_payment")
     * @Template("UserBundle:Payment:set_payment.html.twig")
     */
    public function SetPaymentAction()
    {
        $id= $this->GetSession()->get("id");
        $users = $this->_userRepository->GetUserById($id);
        $setting = $this->_setttingsRepository->GetSetting();

        return array(
            'user' => $users,
            'setting' => $setting
        );
    }


    /**
     * @Route("/ajax/set/payment",name="user_ajax_set_payment")
     */
    public function AjaxSetPaymentAction(Request $request)
    {
        $data = $request->request->all();
        $payment = New Payment();

        $payment->UserId        = $this->GetSession()->get("id");
        $payment->Balance       = $data["balance"];
        $payment->PaymentInfo   = $data["bank"];
        $payment->Status        = "wait";
        $payment->Type          = "notification";

        $getPayment = $this->_paymentRepository->AddPayment($payment);

        return new JsonResponse(array(
            'success' => $getPayment
        ));

    }

}