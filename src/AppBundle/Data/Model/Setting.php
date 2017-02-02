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

    public function MapFrom($data){
        $this->ID               = $data["ID"];
        $this->Title            = $data["title"];
        $this->HeaderPpc        = $data["header_ppc"];
        $this->InterstitialPpc  = $data["interstitial_ppc"];

        return $this;
    }
}