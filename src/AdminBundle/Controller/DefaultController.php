<?php

namespace AdminBundle\Controller;

use AppBundle\Data\Repository\LabelRepository;
use AppBundle\Data\Repository\UserRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route(service="admin.default_controller")
 */
class DefaultController extends BaseController
{
     private $_userRepository;
     private $_labelRepository;

    function __construct(UserRepository $userRepository, LabelRepository $labelRepository)
    {
        $this->_userRepository = $userRepository;
        $this->_labelRepository = $labelRepository;

    }
    /**
     * @Route("/",name="admin")
     * @Template("AdminBundle:Default:index.html.twig")
     */
    public function indexAction()
    {
        $users = $this->_userRepository->GetUser();
        return array(
            'users' => $users
        );
    }

    /**
     * @Route("/login",name="admin_login")
     * @Template("AdminBundle:Default:login.html.twig")
    */
    public function LoginAction()
    {
       if($this->GetSession()->get("email") != null && $this->GetSession()->get('usertype') == 'admin')
           return $this->redirect('/admin');

        return array();
    }

    /**
     * @Route("/ajax/login",name="admin_ajax_login")
     */
    public function AjaxLoginAction(Request $request)
    {

        $email = $request->request->get('email');
        $password = $request->request->get('password');
        $user = $this->_userRepository->LoginUser($email, $password, 'admin');
        if ($user["user"] != false) {

            $this->GetSession()->set('id', $user["user"]->ID);
            $this->GetSession()->set('email', $user["user"]->Email);
            $this->GetSession()->set('usertype', $user["user"]->UserType);

            return new JsonResponse(array(
                'success' => true,
                'message' => "Başarılı"
            ));
        }

        return new JsonResponse(array(
            'success' => false,
            'message' => $user["message"]
        ));


    }
    /**
     * @Route("/logout",name="logout")
     */
    public function LogoutAction()
    {
        $this->GetSession()->clear();
        return $this->redirect('/admin');

    }

    /**
     * @Route("/label/list/{lang}/{limit}/{page}/",name="label_list")
     * @Template("AdminBundle:Label:list.html.twig")
     */
    public function LabelListAction(Request $request,$lang,$limit,$page)
    {

        $searchKey = $request->get('searchKey',null);
        $langCode = $this->_labelRepository->getLanguageById($lang);
        $labels = $this->_labelRepository->getLabelByLanguage($lang,$page*$limit,$limit,$searchKey);
        return array(
            'labels' => $labels,
            'lang' => $lang,
            'langCode'  => $langCode->Code
        );
    }

    /**
     * @Route("/language/list",name="language_list")
     * @Template("AdminBundle:Label:language_list.html.twig")
     */
    public function LanguageListAction()
    {
        $languages = $this->_labelRepository->getLanguage();
        return array(
            'languages' => $languages
        );
    }


    /**
     * @Route("/label/update/{id}",name="label_update")
     * @Template("AdminBundle:Label:update.html.twig")
     */
    public function LabelUpdateAction(Request $request,$id)
    {

        $label = $this->_labelRepository->getLabelById($id);
        return array(
            'label' => $label
        );
    }

    /**
     * @Route("/ajax/label/update/",name="ajax_label_update")
     */
    public function AjaxLabelUpdateAction(Request $request)
    {
        $id = $request->get("id");
        $label = $request->get("label");
        $labelCode = $request->get("label_code");
        $label = $this->_labelRepository->LabelUpdate($id,$label,$labelCode);
        return new JsonResponse(array(
            'success' => $label
        ));
    }
    /**
     * @Route("/label/add/{id}",name="label_add")
     * @Template("AdminBundle:Label:add.html.twig")
     */
    public function LabelAddAction(Request $request ,$id)
    {
        return array(
            'language' => $id
        );
    }



    /**
     * @Route("/ajax/label/add/",name="ajax_label_add")
     */
    public function AjaxLabelAddAction(Request $request)
    {

        $language_id = $request->get("language_id");
        $label = $request->get("label");
        $labelCode = $request->get("label_code");
        $label = $this->_labelRepository->LabelAdd($language_id,$label,$labelCode);
        return new JsonResponse(array(
            'success' => $label
        ));
    }
}
