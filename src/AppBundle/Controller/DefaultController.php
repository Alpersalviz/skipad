<?php

namespace AppBundle\Controller;

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

    public function __construct(UserRepository $userRepository, UrlRepository $urlRepository)
    {
        $this->_userRepository = $userRepository;
        $this->_urlRepository  = $urlRepository;
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
        return array(
            'url' => $this->_urlRepository->GetByRedirectUrl($url)
        );
    }

 


}
