<?php

namespace AppBundle\Controller;

use AppBundle\Data\Model\AdIp;
use AppBundle\Data\Repository\AdvertiserRepository;
use AppBundle\Data\Repository\AdIpRepository;
use AppBundle\Data\Repository\SettingsRepository;
use AppBundle\Data\Repository\UrlRepository;
use AppBundle\Data\Repository\UserRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route(service="app.default_controller")
 */
class DefaultController extends BaseController
{
    private $_userRepository;

    private $_urlRepository;

    private $_advertiserRepository;

    private $_adIpRepository;

    private $_settingRepository;

    public function __construct(UserRepository $userRepository, UrlRepository $urlRepository , AdvertiserRepository $advertiserRepository , AdIpRepository $adIpRepository ,SettingsRepository $settingsRepository)
    {
        $this->_userRepository = $userRepository;
        $this->_urlRepository  = $urlRepository;
        $this->_advertiserRepository = $advertiserRepository;
        $this->_adIpRepository = $adIpRepository;
        $this->_settingRepository = $settingsRepository;
    }

    /**
     * @Route("/",name="homepage")
     * @Template("AppBundle:Default:index.html.twig")
     */
    public function indexAction(Request $request)
    {
        return array();
    }


    /**
     * @Route("/{url}",name="homepage_url_redirect")
     * @Template("AppBundle:Default:redirect.html.twig")
     */
    public function UrlRedirectAction($url)
    {
        $interstitial = $this->_advertiserRepository->GetAdsByType('interstitial');
        $header = $this->_advertiserRepository->GetAdsByType('header_banner');
        $urlObj = $this->_urlRepository->GetByRedirectUrl($url);

        return array(
            'url' => $urlObj,
            'interAd' => $interstitial,
            'headerAd' => $header

        );
    }
    /**
     * @Route("/UrlConfirmRedirectAction/",name="homepage_url_confirm_redirect")
     */
    public function UrlConfirmRedirectAction(Request $request)
    {

        $data = $request->request->all();
        $response = $data["g-recaptcha-response"];
        $url = $data["url"];
        $urlId = $data["urlId"];
        $ip = $request->getClientIp();
        $headerId = $data["headerId"];
        $interId = $data["interId"];
        $setting = $this->_settingRepository->GetSetting();


        /*ReCatpcha  Confirm*/
        $ch = curl_init('https://www.google.com/recaptcha/api/siteverify');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "secret=6LcLXBQUAAAAAI1DbzphORMWLSuNoY1YamdIgBLb&response=".$response."&remoteip=".$ip);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $captchaResponse = json_decode(curl_exec($ch));
        curl_close($ch);

        if(!$captchaResponse->success)
            return $this->redirect("/".$url);

        //captcha success
        $headerBannerIpcount = $this->_adIpRepository->GetAdCountByIp($ip,$urlId,'header_banner');
        $interstitialIpcount = $this->_adIpRepository->GetAdCountByIp($ip,$urlId,'interstitial');
      
        $adIp = new AdIp();
        $adIp->UrlId = $urlId;
        $adIp->Ip = $ip;

        $balance=0;
        //reklamın gösterimini update et ve parayı düş
        if( $headerBannerIpcount == 0 && $headerId !=0 ){
            $adIp->AdId = $headerId;
            $adIp->AdType = 'header_banner';
            $this->_adIpRepository->AddAdIp($adIp);
            $balance +=$setting->HeaderPpcPublisher;
            $this->_advertiserRepository->UpdateImpression($headerId);
        }

        if($interstitialIpcount == 0 && $interId !=0 ) {
            $adIp->AdId = $interId;
            $adIp->AdType = 'interstitial';
            $this->_adIpRepository->AddAdIp($adIp);
            $balance +=$setting->InterstitialPpcPublisher;
            $this->_advertiserRepository->UpdateImpression($interId);
        }

        $urlObj = $this->_urlRepository->GetUrlById($urlId);

        //publishera ekleyeceğimiz para
        if($balance !=0) {
            $this->_urlRepository->UpdateImpression($urlId,$balance);

            $this->_userRepository->AddBalance($urlObj->UserId,$balance);
        }


        return $this->redirect($urlObj->RedirectUrl);
    }
 


}
