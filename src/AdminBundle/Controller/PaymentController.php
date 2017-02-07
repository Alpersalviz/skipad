<?php
/**
 * Created by PhpStorm.
 * User: AlperSalviz
 * Date: 6.2.2017
 * Time: 16:52
 */

namespace AdminBundle\Controller;
use AppBundle\Data\Repository\PaymentRepository;
use AppBundle\Data\Repository\UserRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route(service="admin.payment_controller")
 */
class PaymentController extends BaseController
{
    private $_userRepository;

    private $_paymentRepository;

    public function __construct(UserRepository $userRepository,PaymentRepository $paymentRepository)
    {
        $this->_userRepository = $userRepository;
        $this->_paymentRepository = $paymentRepository;

    }

    /**
     * @Route("/payment/get/list",name="payment_get_list")
     * @Template("AdminBundle:Payment:get-list.html.twig")
     */
    public function PaymentGetListAction(){

        return array(
            'userPayments' => $this->_paymentRepository->GetUserPayments("send")
        );

    }
    /**
     * @Route("/payment/set/list",name="payment_set_list")
     * @Template("AdminBundle:Payment:set-list.html.twig")
     */
    public function PaymentSetListAction(){

        return array(
            'userPayments' => $this->_paymentRepository->GetUserPayments("notification")
        );

    }
    /**
     * @Route("/payment/ajax/confirm",name="admin_payment_confirm")
     */
    public function AjaxConfirmPaymentAction(Request $request){

        $data = $request->request->all();
        $id = $data["id"];

        $updateStatus = $this->_paymentRepository->UpdateStatus($id);


        return new JsonResponse(array(
            'success' => $updateStatus,
            'message' => "Güncellendi"
        ));

    }

    /**
     * @Route("/payment/ajax/set/confirm",name="admin_payment_set_confirm")
     */
    public function AjaxConfirmSetPaymentAction(Request $request){

        $data = $request->request->all();
        $id = $data["id"];
        $userId = $data["userId"];
        $balance = $data["balance"];

        $balanceSave = $this->_paymentRepository->SetPayment($userId,$balance);

        if ($balance == false)
            return new JsonResponse(array(
                'success' => $balance
            ));

        $updateStatus = $this->_paymentRepository->UpdateStatus($id);


        return new JsonResponse(array(
            'success' => $updateStatus 
        ));

    }

}