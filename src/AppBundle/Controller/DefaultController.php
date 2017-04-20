<?php

namespace AppBundle\Controller;

use AppBundle\Data\Model\AdIp;
use AppBundle\Data\Repository\AdvertiserRepository;
use AppBundle\Data\Repository\AdIpRepository;
use AppBundle\Data\Repository\PpcCountryRepository;
use AppBundle\Data\Repository\SettingsRepository;
use AppBundle\Helper\Geoiploc;
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

    private $_ppcCountryRepository;

    public function __construct(UserRepository $userRepository, UrlRepository $urlRepository , AdvertiserRepository $advertiserRepository , AdIpRepository $adIpRepository ,SettingsRepository $settingsRepository , PpcCountryRepository $ppcCountryRepository)
    {
        $this->_userRepository = $userRepository;
        $this->_urlRepository  = $urlRepository;
        $this->_advertiserRepository = $advertiserRepository;
        $this->_adIpRepository = $adIpRepository;
        $this->_settingRepository = $settingsRepository;
        $this->_ppcCountryRepository = $ppcCountryRepository;
    }

    /**
     * @Route("/",name="homepage")
     * @Template("AppBundle:Default:index.html.twig")
     */
    public function indexAction(Request $request)
    {
            return $this->redirect('home/tr');

        return array();
    }

    /**
     * @Route("/home/{code}",name="homepageLanguage")
     * @Template("AppBundle:Default:index.html.twig")
     */
    public function indexLanguageAction(Request $request,$code)
    {
        $this->RemoveSession($code);
        $this->GetSession()->set('language', $code);

        if ($this->GetSession()->get("email") != null && $this->GetSession()->get('usertype') == 'user')
            return $this->redirect('/user');

        return array();
    }
    /**
     * @Route("/ajax/url/add",name="ajax_url_add")
     */
    public function AjaxAddUrlAction(Request $request){
        $redirectUrl = $request->request->get('url');
        $url = $this->getRandomString();
        $userId = $this->GetSession()->get("id") == null ? 0 : $this->GetSession()->get("id") ;

        $randomUrlCount = $this->_urlRepository->GetRandomUrl($url);

        if ($randomUrlCount->ListSize != 0)
            $url = $this->getRandomString();

        $addUrl = $this->_urlRepository->AddUrl($redirectUrl,$url,$userId,'link');

        $nowAddUrl = $this->_urlRepository->GetUrlById($addUrl['id']);

        return new JsonResponse(array(
            'success' => $addUrl['succsess'],
            'link' => $nowAddUrl
        ));

    }

    /**
     * @Route("/{url}",name="homepage_url_redirect")
     * @Template("AppBundle:Default:redirect.html.twig")
     */
    public function UrlRedirectAction($url)
    {
        $interstitial = $this->_advertiserRepository->GetAdsByType('interstitial');
        $header = $this->_advertiserRepository->GetAdsByType('header_banner');
        $popup = $this->_advertiserRepository->GetAdsByType('popup');
        $urlObj = $this->_urlRepository->GetByRedirectUrl($url);

        return array(
            'url'       => $urlObj,
            'interAd'   => $interstitial,
            'headerAd'  => $header,
            'popupAd'   => $popup

        );
    }
    /**
     * @Route("/redirect/popup/{url}",name="homepage_popup_redirect")
     */
    public function PopupRedirectAction($url,Request $request)
    {
        $popup = $this->_advertiserRepository->GetAdsByType('popup');
        $popupId = $popup["ID"];
        $urlObj = $this->_urlRepository->GetByRedirectUrl($url);
        $urlId = $urlObj->ID;
        $userId =  $request->query->get('userid');
        $ip = $request->getClientIp();
        $geoiploc = new Geoiploc();
        $country = $geoiploc->getCountryFromIP($ip);

        $is3g = $geoiploc->is3g($ip);

        $setting = $this->_settingRepository->GetSetting();

        $popupIpcount = $this->_adIpRepository->GetAdCountByIp($ip,$urlId,'popup');

        $ppcCountry = $this->_ppcCountryRepository->GetCountryPpc($country,$is3g);

        $balance = 0;
        if($popupIpcount == 0 && $popupId !=0 ) {
            $adIp = new AdIp();
            $adIp->UrlId = $urlId;
            $adIp->Ip = $ip;

            $adIp->AdId = $popupId;
            $adIp->AdType = 'popup';
            $this->_adIpRepository->AddAdIp($adIp);
            if (!isset($ppcCountry["popup"])) {
                $balance += $setting->PopupPpcPublisher;
            } else {
                $balance += $ppcCountry["popup"]->PpcPublisher;
            }
            $this->_advertiserRepository->UpdateImpression($popupId, (!isset($ppcCountry["popup"]) ? 0 : $ppcCountry["popup"]->Ppc));
        }
        if($balance != 0) {
            $this->_urlRepository->UpdateVisitor($urlId,$balance);

            $this->_userRepository->AddBalance($urlObj->UserId,$balance);
        }else{
            $this->_urlRepository->UpdateImpression($urlId);

        }
            return $this->redirect($popup["url"]);
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
        $geoiploc = new Geoiploc();
        $country = $geoiploc->getCountryFromIP($ip);

        $is3g = $geoiploc->is3g($ip);

        $headerId = $data["headerId"];
        $interId = $data["interId"];
        $popupId = $data["popupId"];
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
        $popupIpcount = $this->_adIpRepository->GetAdCountByIp($ip,$urlId,'popup');

        $ppcCountry = $this->_ppcCountryRepository->GetCountryPpc($country,$is3g);
       

        $adIp = new AdIp();
        $adIp->UrlId = $urlId;
        $adIp->Ip = $ip;

        $balance=0;
        //reklamın gösterimini update et ve parayı düş
        if( $headerBannerIpcount == 0 && $headerId !=0 ){
            $adIp->AdId = $headerId;
            $adIp->AdType = 'header_banner';
            $this->_adIpRepository->AddAdIp($adIp);
            if(!isset($ppcCountry["header_banner"])){
                $balance +=$setting->HeaderPpcPublisher;
            }else{
                $balance +=$ppcCountry["header_banner"]->PpcPublisher;
            }
            $this->_advertiserRepository->UpdateImpression($headerId,(!isset($ppcCountry["header_banner"]) ? 0 : $ppcCountry["header_banner"]->Ppc ));
        }

        if($interstitialIpcount == 0 && $interId !=0 ) {
            $adIp->AdId = $interId;
            $adIp->AdType = 'interstitial';
            $this->_adIpRepository->AddAdIp($adIp);
            if(!isset($ppcCountry["interstitial"])){
                $balance +=$setting->InterstitialPpcPublisher;
            }else{
                $balance +=$ppcCountry["interstitial"]->PpcPublisher;
            }
            $this->_advertiserRepository->UpdateImpression($interId,(!isset($ppcCountry["interstitial"]) ? 0 : $ppcCountry["interstitial"]->Ppc ));
        }
        if($popupIpcount == 0 && $popupId !=0 ) {
            $adIp->AdId = $popupId;
            $adIp->AdType = 'popup';
            $this->_adIpRepository->AddAdIp($adIp); 
            if(!isset($ppcCountry["popup"])){
                $balance +=$setting->PopupPpcPublisher;
            }else{
                $balance +=$ppcCountry["popup"]->PpcPublisher;
            }
            $this->_advertiserRepository->UpdateImpression($popupId , (!isset($ppcCountry["popup"]) ? 0 : $ppcCountry["popup"]->Ppc ));
        }
        $urlObj = $this->_urlRepository->GetUrlById($urlId);

        //publishera ekleyeceğimiz para
        if($balance != 0) {
            $this->_urlRepository->UpdateVisitor($urlId,$balance);

            $this->_userRepository->AddBalance($urlObj->UserId,$balance);
        }else{
            $this->_urlRepository->UpdateImpression($urlId);

        } 

        if(array_key_exists('returnUrl', $data)){
            return $this->redirect(base64_decode($data["returnUrl"]));
        }else{
            return $this->redirect($urlObj->RedirectUrl);
        }

    }

    function getRandomString($length = 6) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $string = '';

        for ($i = 0; $i < $length; $i++) {
            $string .= $characters[mt_rand(0, strlen($characters) - 1)];
        }

        return $string;
    }



}
