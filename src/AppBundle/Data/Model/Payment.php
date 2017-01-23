<?php
/**
 * Created by PhpStorm.
 * User: AlperSalviz
 * Date: 23.1.2017
 * Time: 22:07
 */

namespace AppBundle\Data\Model;


class Payments
{
    public $ID;
    public $UserId;
    public $PaymentType;
    public $Status;
    public $Balance;
    public $PaymentInfo;

    public function MapFrom($data){
        $this->ID = $data["ID"];
        $this->UserId = $data["user_id"];
        $this->PaymentType = $data["payment_type"];
        $this->Status = $data["status"];
        $this->Balance = $data["balance"];
        $this->PaymentInfo = $data["payment_info"];

        return $this;
    }

}