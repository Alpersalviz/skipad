<?php
/**
 * Created by PhpStorm.
 * User: AlperSalviz
 * Date: 7.2.2017
 * Time: 11:31
 */

namespace AdminBundle\Controller;

use AppBundle\Data\Repository\AdvertiserRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route(service="admin.advertiser_controller")
 */
class AdvertiserController extends BaseController
{

    private $_advertiserRepository;

    public function __construct(AdvertiserRepository $advertiserRepository)
    {
        $this->_advertiserRepository = $advertiserRepository;
    }
    /**
     * @Route("/advertiser/list",name="admin_advertiser_list")
     * @Template("AdminBundle:Advertiser:list.html.twig")
     */
    public function ListAction(){

        return array(
            'advertisers' => $this->_advertiserRepository->GetAdsUser()
        );

    }

    /**
     * @Route("/advertiser/ajax/cancel",name="admin_advertiser_cancel")
     */
    public function AjaxCancelAdvertiserAction(Request $request){

        $data = $request->request->all();
        $id = $data["id"];
        $publish =$data["publish"];

        $cancel = $this->_advertiserRepository->CancelAdd($id,$publish);


        return new JsonResponse(array(
            'success' => $cancel,
            'message' => "Güncellendi"
        ));

    }


    /**
     * @Route("/advertiser/update/{id}",name="admin_advertiser_update")
     * @Template("AdminBundle:Advertiser:update.html.twig")
     */
    public function UpdateAdvertiserAction($id){

        return array(
            'advertiser' => $this->_advertiserRepository->GetAdsById($id)
        );

    }

    /**
     * @Route("/advertiser/ajax/update",name="admin_ajax_advertiser_update")
     */
    public function AjaxUpdateAdvertiserAction(Request $request){


        $data = $request->request->all();
        $id = $data["id"];
        $title = $data["title"];
        $url = $data["url"];
        $ppc = $data["ppc"];

        $update = $this->_advertiserRepository->UpdateAddByAdmin($id,$url,$title,$ppc);


        return new JsonResponse(array(
            'success' => $update,
            'message' => "Güncellendi"
        ));

    }


}