<?php
/**
 * Created by PhpStorm.
 * User: AlperSalviz
 * Date: 23.1.2017
 * Time: 22:12
 */

namespace AppBundle\Data\Model;


class Setting
{
    public $ID;
    public $Title;
    public $HeaderPpc;
    public $InterstitialPpc;
    public $PaymentInfo;
    public $PaymentBanks;
    public $MinimumPayment;
    public $HeaderPpcPublisher;
    public $InterstitialPpcPublisher;

    public function MapFrom($data){
        $this->ID                       = $data["ID"];
        $this->Title                    = $data["title"];
        $this->HeaderPpc                = (double)$data["header_ppc"];
        $this->InterstitialPpc          = (double)$data["interstitial_ppc"];
        $this->PaymentInfo              = $data["payment_info"];
        $this->PaymentBanks             = $data["payment_banks"];
        $this->MinimumPayment           = $data["minimum_payment"];
        $this->HeaderPpcPublisher       = $data["header_ppc_publisher"];
        $this->InterstitialPpcPublisher = $data["interstitial_ppc_publisher"];

        return $this;
    }
}