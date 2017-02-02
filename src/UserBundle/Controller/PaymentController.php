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

    function __construct(UserRepository $userRepository , PaymentRepository $paymentRepository)
    {
        $this->_userRepository = $userRepository;
        $this->_paymentRepository = $paymentRepository;

    }

    /**
     * @Route("/get/payment",name="user_get_payment")
     * @Template("UserBundle:Payment:get_payment.html.twig")
     */
    public function GetPaymentAction()
    {
        $id= $this->GetSession()->get("id");
        $users = $this->_userRepository->GetUserById($id);
        return array(
            'user' => $users
        );
    }

    /**
     * @Route("/ajax/get/payment",name="user_ajax_get_payment")
     */
    public function AjaxGetPaymentAction(Request $request)
    {
        $data = $request->request->all();
        $payment = New Payment();

        $payment->UserId        = $this->GetSession()->get("id");
        $payment->Balance       = $data["balance"];
        $payment->PaymentInfo   = $data["payment_info"];
        $payment->PaymentType   = $data["payment_type"];
        $payment->Status        = "wait";

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
        return array(
            'user' => $users
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
        $payment->PaymentInfo   = $data["payment_info"];
        $payment->PaymentType   = $data["payment_type"];
        $payment->Status        = "wait";

        $getPayment = $this->_paymentRepository->AddPayment($payment);

        return new JsonResponse(array(
            'success' => $getPayment
        ));

    }

}