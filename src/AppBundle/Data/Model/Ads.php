<?php
/**
 * Created by PhpStorm.
 * User: AlperSalviz
 * Date: 23.1.2017
 * Time: 21:45
 */

namespace AppBundle\Data\Model;


class Ads
{

    public $ID;
    public $Title;
    public $Url;
    public $AdType;
    public $Impression;
    public $Earn;
    public $UserId;
    public $CreatedDate;
    public $CreatedIp;
    public $Ppc;
    public $FirstPrice;
    public $CurrentPrice;

    public function MapFrom($data){
        $this->ID = $data["ID"];
        $this->Title = $data["title"];
        $this->Url = $data["url"];
        $this->AdType = $data["ad_type"];
        $this->Impression = $data["impression"];
        $this->Earn = $data["earn"];
        $this->UserId = $data["userid"];
        $this->CreatedDate = $data["created_data"];
        $this->CreatedIp = $data["created_ip"];
        $this->Ppc = $data["ppc"];
        $this->FirstPrice = $data["first_price"];
        $this->CurrentPrice = $data["current_price"];

        return $this;
    }

}