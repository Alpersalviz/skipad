<?php
/**
 * Created by PhpStorm.
 * User: AlperSalviz
 * Date: 7.2.2017
 * Time: 22:06
 */

namespace AppBundle\Data\Model;


class PpcCountry
{
    public $ID;
    public $Ppc;
    public $PpcPublisher;
    public $CountryCode;
    public $Is3g;
    public $AdType;

    public function MapFrom($data){
        $this->ID                       = $data["ID"];
        $this->Ppc                      = $data["ppc"];
        $this->PpcPublisher             = (double)$data["ppc_publisher"];
        $this->CountryCode              = (double)$data["country_code"];
        $this->Is3g                     = $data["is_3g"];
        $this->AdType                   = $data["ad_type"];

        return $this;
    }

}