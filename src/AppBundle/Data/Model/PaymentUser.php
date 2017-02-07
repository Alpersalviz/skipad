<?php
/**
 * Created by PhpStorm.
 * User: AlperSalviz
 * Date: 6.2.2017
 * Time: 17:20
 */

namespace AppBundle\Data\Model;


class PaymentUser
{

    public $Email;
    public $Name;
    public $SurName;
    public $UserId;
    public $PhoneNumber;
    public $Balance;
    public $PaymentDate;
    public $PaymentInfo;
    public $Status;
    public $PaymentId;

    public function MapFrom($data){
        $this->Email = $data["email"];
        $this->Name = $data["name"];
        $this->SurName = $data["surname"];
        $this->UserId = $data["user_id"];
        $this->PhoneNumber = $data["phone_number"];
        $this->Balance = $data["balance"];
        $this->PaymentDate = $data["payment_date"];
        $this->PaymentInfo = $data["payment_info"];
        $this->Status = $data["status"];
        $this->PaymentId = $data["payment_id"];

        return $this;
    }

}