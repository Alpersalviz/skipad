<?php
/**
 * Created by PhpStorm.
 * User: AlperSalviz
 * Date: 23.1.2017
 * Time: 22:03
 */

namespace AppBundle\Data\Model;


class AdIp
{
    public $ID;
    public $AdId;
    public $Ip;
    public $Date;
    public $UrlId;
    public $AdType;

    public function MapFrom($data){
        $this->ID = $data["ID"];
        $this->AdId = $data["ad_id"];
        $this->Ip = $data["ip"];
        $this->Date = $data["date"];
        $this->UrlId = $data["url_id"];
        $this->AdType = $data["ad_type"];
        
        return $this;
    }

}