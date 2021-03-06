<?php
/**
 * Created by PhpStorm.
 * User: AlperSalviz
 * Date: 30.1.2017
 * Time: 13:53
 */

namespace UserBundle\Controller;

use AppBundle\Data\Repository\UrlRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route(service="user.url_controller")
 */
class UrlController extends BaseController
{
    private $_urlRepository;

    public function __construct(UrlRepository $urlrepository)
    {
        $this->_urlRepository = $urlrepository;

    }

    /**
 * @Route("/url/list",name="user_url_list")
 * @Template("UserBundle:Url:list.html.twig")
 */
    public function ListUrlAction(){

        return array(
            'urls' => $this->_urlRepository->GetByUserIdUrl($this->GetSession()->get('id'),'link')
        );

    }
    /**
     * @Route("/url/changer/list",name="user_url_changer_list")
     * @Template("UserBundle:Url:changer_list.html.twig")
     */
    public function ListUrlChangerAction(){

        return array(
            'urls' => $this->_urlRepository->GetByUserIdUrl($this->GetSession()->get('id'),'changelink')
        );

    }

    /**
     * @Route("/popup/list",name="user_popup_list")
     * @Template("UserBundle:Url:popup_list.html.twig")
     */
    public function ListPopupAction(){

        return array(
            'urls' => $this->_urlRepository->GetByUserIdUrl($this->GetSession()->get('id'),'popup')
        );

    }
    /**
     * @Route("/url/update/{id}",name="user_url_update")
     * @Template("UserBundle:Url:update.html.twig")
     */
    public function UpdateUrlAction($id){

        return array(
            'url' => $this->_urlRepository->GetUrlById($id)
        );

    }

    /**
     * @Route("/url/add",name="user_url_add")
     * @Template("UserBundle:Url:add.html.twig")
     */
    public function AddUrlAction(){

        return array();

    }
    /**
     * @Route("/get/popup/code/{id}",name="user_get_popup_code")
     * @Template("UserBundle:Url:get_popup_code.html.twig")
     */
    public function GetPopupCodeAction($id){

        return array(
            'url' => $this->_urlRepository->GetUrlById($id)
        );

    }
    /**
     * @Route("/get/url/code/{id}",name="user_get_url_code")
     * @Template("UserBundle:Url:get_url_code.html.twig")
     */
    public function GetUrlCodeAction($id){

        return array(
            'url' => $this->_urlRepository->GetUrlById($id)
        );

    }
    /**
     * @Route("/url/changer/add",name="user_url_changer_add")
     * @Template("UserBundle:Url:changer_add.html.twig")
     */
    public function AddChangerUrlAction(){

        return array();
    }

    /**
     * @Route("/popuo/add",name="user_popup_add")
     * @Template("UserBundle:Url:popup_add.html.twig")
     */
    public function AddPopupAction(){

        return array();
    }
    /**
     * @Route("/url/multi/add",name="user_url_multi_add")
     * @Template("UserBundle:Url:multi_add.html.twig")
     */
    public function AddUrlMultiAction(){

        return array();

    }
    /**
     * @Route("/ajax/url/add",name="user_ajax_url_add")
     */
    public function AjaxAddUrlAction(Request $request){
        $redirectUrl = $request->request->get('url');
        $url = $this->getRandomString();
        $userId = $this->GetSession()->get("id");

        $randomUrlCount = $this->_urlRepository->GetRandomUrl($url);
        
            if ($randomUrlCount->ListSize != 0)
                $url = $this->getRandomString();

        $addUrl = $this->_urlRepository->AddUrl($redirectUrl,$url,$userId,'link');

        return new JsonResponse(array(
            'success' => $addUrl
        ));

    }
    /**
     * @Route("/ajax/url/changer/add",name="user_ajax_url_changer_add")
     */
    public function AjaxAddChangerUrlAction(Request $request){
        $redirectUrl = $request->request->get('url');
        $url = $this->getRandomString();
        $userId = $this->GetSession()->get("id");

        $randomUrlCount = $this->_urlRepository->GetRandomUrl($url);

        if ($randomUrlCount->ListSize != 0)
            $url = $this->getRandomString();

        $addUrl = $this->_urlRepository->AddUrl($redirectUrl,$url,$userId,'changelink');

        return new JsonResponse(array(
            'success' => $addUrl
        ));

    }

    /**
     * @Route("/ajax/popup/add",name="user_ajax_popup_add")
     */
    public function AjaxAddPopupUrlAction(Request $request){
        $redirectUrl = $request->request->get('url');
        $url = $this->getRandomString();
        $userId = $this->GetSession()->get("id");

        $randomUrlCount = $this->_urlRepository->GetRandomUrl($url);

        if ($randomUrlCount->ListSize != 0)
            $url = $this->getRandomString();

        $addUrl = $this->_urlRepository->AddUrl($redirectUrl,$url,$userId,'popup');

        return new JsonResponse(array(
            'success' => $addUrl
        ));

    }

    /**
     * @Route("/ajax/url/multi/add",name="user_ajax_url_multi_add")
     */
    public function AjaxAddMultiUrlAction(Request $request){
        $multiUrl = $request->request->get('multi_url');
        $redirectUrls = explode("\n",$multiUrl);
       
        $userId = $this->GetSession()->get("id");
        foreach ($redirectUrls as $redirectUrl){
            $url = $this->getRandomString();


            $randomUrlCount = $this->_urlRepository->GetRandomUrl($url);

            if ($randomUrlCount->ListSize != 0)
                $url = $this->getRandomString();

            $addUrl = $this->_urlRepository->AddUrl($redirectUrl,$url,$userId,'link');
        }


        return new JsonResponse(array(
            'success' => $addUrl
        ));

    }
    /**
     * @Route("/ajax/url/delete",name="user_ajax_url_delete")
     */
    public function AjaxDeleteUrlAction(Request $request){
        $id = $request->request->get('id');

        return new JsonResponse(array(
            'success' => $this->_urlRepository->DeleteUrl($id)
        ));

    }
    /**
     * @Route("/ajax/url/update",name="user_ajax_url_update")
     */
    public function AjaxUpdateUrlAction(Request $request){
        $id = $request->request->get('id');
        $url = $request->request->get('url'); 


        return new JsonResponse(array(
            'success' => $this->_urlRepository->UpdateUrl($id,$url)
        ));

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