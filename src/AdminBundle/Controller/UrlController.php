<?php
/**
 * Created by PhpStorm.
 * User: AlperSalviz
 * Date: 7.2.2017
 * Time: 11:57
 */

namespace AdminBundle\Controller;
use AppBundle\Data\Repository\UrlRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route(service="admin.url_controller")
 */
class UrlController extends BaseController
{
    private $_urlRepository;

    public function __construct(UrlRepository $urlRepository)
    {
        $this->_urlRepository = $urlRepository;

    }

    /**
     * @Route("/url/list",name="admin_url_list")
     * @Template("AdminBundle:Url:list.html.twig")
     */
    public function ListUrlAction(){

        return array(
            'urls' => $this->_urlRepository->GetUrlUser()
        );

    }


    /**
     * @Route("/ajax/url/delete",name="admin_ajax_url_delete")
     */
    public function AjaxDeleteUrlAction(Request $request){
        $id = $request->request->get('id');

        return new JsonResponse(array(
            'success' => $this->_urlRepository->DeleteUrl($id)
        ));

    }

}