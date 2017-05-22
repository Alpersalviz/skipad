<?php
/**
 * Created by PhpStorm.
 * User: AlperSalviz
 * Date: 7.2.2017
 * Time: 11:31
 */

namespace AdminBundle\Controller;

use AppBundle\Data\Model\PpcCountry;
use AppBundle\Data\Repository\AdvertiserRepository;
use AppBundle\Data\Repository\PpcCountryRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route(service="admin.ppc_country_controller")
 */
class PpcCountryController extends BaseController
{

    private $_ppcCountryRepository;

    public function __construct(PpcCountryRepository $ppcCountryRepository)
    {
        $this->_ppcCountryRepository = $ppcCountryRepository;
    }
    /**
     * @Route("/ppcCountry/list",name="admin_ppcCountry_list")
     * @Template("AdminBundle:PpcCountry:list.html.twig")
     */
    public function ListAction(){

        return array(
            'ppcCountries' => $this->_ppcCountryRepository->GetAllPpc()
        );

    }


    /**
     * @Route("/ppcCountry/update/{id}",name="admin_ppcCountry_update")
     * @Template("AdminBundle:PpcCountry:update.html.twig")
     */
    public function UpdatePpcCountryAction($id){

        return array(
            'ppc' => $this->_ppcCountryRepository->GetPpcById($id)
        );

    }




    /**
     * @Route("/ppcCountry/add",name="admin_ppcCountry_add")
     * @Template("AdminBundle:PpcCountry:add.html.twig")
     */
    public function AddPpcCountryAction(){

        return array( );

    }

    /**
     * @Route("/ppcCountry/ajax/add",name="admin_ajax_appcCountry_add")
     */
    public function AjaxAddPpcCountryAction(Request $request){


        $data = $request->request->all();

        $ppcCountry = new PpcCountry();

        if(array_key_exists('is_3g',$data)){
            $ppcCountry->Is3g = (int)$data["is_3g"];
        }else{
            $ppcCountry->Is3g = (int)0;
        }

        $ppcCountry->ID = null;

        $ppcCountry->CountryCode = $data["country_code"];
        $ppcCountry->PpcPublisher = (double)$data["ppc_publisher"];
        $ppcCountry->AdType = $data["ad_type"];
        $ppcCountry->Ppc = (double)$data["ppc"];

        $add = $this->_ppcCountryRepository->AddPpcCountry($ppcCountry);

        return new JsonResponse(array(
            'success' => $add,
            'message' => "Eklendi"
        ));

    }
    /**
     * @Route("/ppcCountry/ajax/update",name="admin_ajax_appcCountry_update")
     */
    public function AjaxUpdatePpcCountryAction(Request $request){



        $data = $request->request->all();

        $ppcCountry = new PpcCountry();

        if(array_key_exists('is_3g',$data)){
            $ppcCountry->Is3g = (int)$data["is_3g"];
        }else{
            $ppcCountry->Is3g = (int)0;
        }

        $ppcCountry->ID = $data["ID"];

        $ppcCountry->CountryCode = $data["country_code"];
        $ppcCountry->PpcPublisher = (double)$data["ppc_publisher"];
        $ppcCountry->AdType = $data["ad_type"];
        $ppcCountry->Ppc = (double)$data["ppc"];

        $update = $this->_ppcCountryRepository->UpdatePpcCountry($ppcCountry);

        return new JsonResponse(array(
            'success' => $update,
            'message' => "Güncellendi"
        ));
    }
    /**
     * @Route("/ppcCountry/ajax/delete",name="admin_ajax_appcCountry_delete")
     */
    public function AjaxDeletePpcCountryAction(Request $request){

        $data = $request->request->all();
        $id = $data['id'];

        $delete = $this->_ppcCountryRepository->DeletePpcCountry($id);

        return new JsonResponse(array(
            'success' => $delete,
            'message' => "Güncellendi"
        ));
    }

}