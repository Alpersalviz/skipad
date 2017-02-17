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
    public $UserId;
    public $CreatedDate;
    public $CreatedIp;
    public $Ppc;
    public $FirstPrice;
    public $CurrentPrice;
    public $Publish;
    public $Email;

    public function MapFrom($data){
        $this->ID = $data["ID"];
        $this->Title = $data["title"];
        $this->Url = $data["url"];
        $this->AdType = $data["ad_type"];
        $this->Impression = $data["impression"];
        $this->UserId = $data["user_id"];
        $this->CreatedDate = $data["created_date"];
        $this->CreatedIp = $data["created_ip"];
        $this->Ppc = $data["ppc"];
        $this->FirstPrice = $data["first_price"];
        $this->CurrentPrice = $data["current_price"];
        $this->Publish = $data["publish"];
        if(array_key_exists('email',$data))
            $this->Email = $data['email'];

        return $this;
    }

}