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
            'urls' => $this->_urlRepository->GetByUserIdUrl($this->GetSession()->get('id'))
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

        $addUrl = $this->_urlRepository->AddUrl($redirectUrl,$url,$userId);

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

            $addUrl = $this->_urlRepository->AddUrl($redirectUrl,$url,$userId);
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