<?php
/**
 * Created by PhpStorm.
 * User: AlperSalviz
 * Date: 30.1.2017
 * Time: 20:54
 */

namespace UserBundle\Controller;

use AppBundle\Data\Model\Ads;
use AppBundle\Data\Repository\AdvertiserRepository;
use AppBundle\Data\Repository\SettingsRepository;
use AppBundle\Data\Repository\UserRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route(service="user.advertiser_controller")
 */
class AdvertiserController extends BaseController
{

    private $_advertiserRepository;
    private $_settingsRepository;
    private $_userRepository;

    public function __construct(AdvertiserRepository $advertiserrepository, SettingsRepository $settingsRepository, UserRepository $userrepository)
    {
        $this->_advertiserRepository = $advertiserrepository;
        $this->_settingsRepository = $settingsRepository;
        $this->_userRepository = $userrepository;

    }

    /**
     * @Route("/advertiser/list",name="user_advertiser_list")
     * @Template("UserBundle:Advertiser:list.html.twig")
     */
    public function ListAdvertiserAction(){

        return array(
            'advertisers' => $this->_advertiserRepository->GetAdsByUserId($this->GetSession()->get("id"))
        );

    }

    /**
     * @Route("/advertiser/update/{id}",name="user_advertiser_update")
     * @Template("UserBundle:Advertiser:update.html.twig")
     */
    public function UpdateAdvertiserAction($id){

        return array(
            'advertiser' => $this->_advertiserRepository->GetAdsById($id)
        );

    }


    /**
     * @Route("/advertiser/add",name="user_advertiser_add")
     * @Template("UserBundle:Advertiser:add.html.twig")
     */
    public function AddAdvertiserAction(){

        return array();

    }
    /**
     * @Route("/advertiser/ajax/update",name="user_ajax_advertiser_update")
     */
    public function AjaxUpdateAdvertiserAction(Request $request){


        $data = $request->request->all();
        $id = $data["id"];
        $title = $data["title"];
        $url = $data["url"];

        $update = $this->_advertiserRepository->UpdateAdd($id,$url,$title);


        return new JsonResponse(array(
            'success' => $update,
            'message' => "Güncellendi"
        ));

    }
    /**
     * @Route("/advertiser/ajax/cancel",name="user_advertiser_cancel")
     */
    public function AjaxCancelAdvertiserAction(Request $request){
 
        $data = $request->request->all();
        $id = $data["id"];

        $cancel = $this->_advertiserRepository->CancelAdd($id);


        return new JsonResponse(array(
            'success' => $cancel,
            'message' => "Güncellendi"
        ));

    }


    /**
     * @Route("/advertiser/ajax/add",name="user_ajax_advertiser_add")
     */
    public function AjaxAddAdvertiserAction(Request $request){


        $data = $request->request->all();
        $id = $this->GetSession()->get('id');
        if((int)$data["first_price"] <= 0)
            return new JsonResponse(array(
                'success' => false,
                'message' => "Bütçe 0'dan küçük olamaz"
            ));
        $setting = $this->_settingsRepository->GetSetting();

        $user = $this->_userRepository->GetUserById($id);
        if($user->Balance < (int)$data["first_price"]){
            return new JsonResponse(array(
                'success' => false,
                'message' => "Yetersiz Bakiye"
            ));
        }

        $ad = new Ads();

        $ad->Title  = $data["title"];
        $ad->Url    = $data["url"];
        $ad->AdType = $data["ad_type"];
        $ad->Impression = 0;
        $ad->UserId = $this->GetSession()->get('id');
        $ad->CreatedDate = date("Y-m-d H:i:s");
        $ad->CreatedIp = $request->getClientIp();
        if($data["ad_type"] == "header_banner"){
            $ad->Ppc = $setting->HeaderPpc;
        }else if($data["ad_type"] == "interstitial"){
            $ad->Ppc = $setting->InterstitialPpc;

        }else{
            $ad->Ppc = 0;
        }
        $ad->CurrentPrice = $data["first_price"];
        $ad->FirstPrice = $data["first_price"];

        $adRepo = $this->_advertiserRepository->AddAd($ad);


        return new JsonResponse(array(
            'success' => $adRepo,
            'message' => "Reklam Eklendi"
        ));

    }

}