<?php
/**
 * Created by PhpStorm.
 * User: AlperSalviz
 * Date: 7.2.2017
 * Time: 13:30
 */

namespace AdminBundle\Controller;

use AppBundle\Data\Model\Setting;
use AppBundle\Data\Repository\SettingsRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route(service="admin.setting_controller")
 */
class SettingController extends BaseController
{
    private $_settingRepository;

    public function __construct(SettingsRepository $settingsRepository)
    {
        $this->_settingRepository = $settingsRepository;
    }


    /**
     * @Route("/setting/update/",name="admin_setting_update")
     * @Template("AdminBundle:Setting:update.html.twig")
     */
    public function UpdateSettingAction(){

        return array(
            'setting' => $this->_settingRepository->GetSetting()
        );

    }


    /**
     * @Route("/setting/ajax/update",name="admin_ajax_setting_update")
     */
    public function AjaxUpdateSettingAction(Request $request){

        $data = $request->request->all();

        $setting = new Setting();

        $setting->PaymentInfo = $data["PaymentInfo"];
        $setting->Title = $data["Title"];
        $setting->HeaderPpc = $data["HeaderPpc"];
        $setting->InterstitialPpc = $data["InterstitialPpc"];
        $setting->PopupPpc = $data["PopupPpc"];
        $setting->PaymentBanks = $data["PaymentBanks"];
        $setting->MinimumPayment = $data["MinimumPayment"];
        $setting->HeaderPpcPublisher = $data["HeaderPpcPublisher"];
        $setting->InterstitialPpcPublisher = $data["InterstitialPpcPublisher"];
        $setting->PopupPpcPublisher = $data["PopupPpcPublisher"];


        $updateSetting = $this->_settingRepository->UpdateSetting($setting);


        return new JsonResponse(array(
            'success' => $updateSetting,
            'message' => "Güncellendi"
        ));

    }

}