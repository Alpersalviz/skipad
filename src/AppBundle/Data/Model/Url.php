<?php
/**
 * Created by PhpStorm.
 * User: AlperSalviz
 * Date: 23.1.2017
 * Time: 22:14
 */

namespace AppBundle\Data\Model;


class Url
{
    public $ID;
    public $Url;
    public $RedirectUrl;
    public $Price;
    public $Impression;
    public $UserId;
    public $CreatedDate;
    public $Visitor;
    public $Email;

    public function MapFrom($data){
        $this->ID = $data["ID"];
        $this->Url = $data["url"];
        $this->RedirectUrl = $data["redirect_url"];
        $this->Price = $data["price"];
        $this->Impression = $data["impression"];
        $this->UserId = $data["user_id"];
        $this->CreatedDate = $data["created_date"];
        $this->Visitor  = $data["visitor"];
        if(array_key_exists('email',$data))
            $this->Email = $data['email'];

        return $this;
    }

}