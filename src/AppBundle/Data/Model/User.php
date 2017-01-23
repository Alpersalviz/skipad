<?php
namespace AppBundle\Data\Model;

class User
{

    public $ID;
    public $Email;
    public $Password;
    public $UserType;
    public $Name;
    public $Address1;
    public $Address2;
    public $City;
    public $Country;
    public $ZipCode;
    public $PhoneNumber;
    public $AccountType;
    public $PaymnetType;
    public $PaymnetInfo;
    public $Balance;
    public $CreateIp;

    /**
     * return User
     */
    public function MapFrom(array $data)
    {
        $this->ID = $data["ID"];
        $this->Email = $data["email"];
        $this->Password = $data["password"];
        $this->UserType = $data["user_type"];
        $this->Address1 = $data["address1"];
        $this->Address2 = $data["address2"];
        $this->City = $data["city"];
        $this->Country = $data["country"];
        $this->ZipCode = $data["zip_code"];
        $this->PhoneNumber = $data["phone_number"];
        $this->AccountType = $data["account_type"];
        $this->PaymnetType = $data["payment_type"];
        $this->PaymnetInfo = $data["payment_info"];
        $this->Balance = $data["balance"];
        $this->CreateIp = $data["created_ip"];
        return $this;

    }

}