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

    public function MapFrom($data){
        $this->ID = $data["ID"];
        $this->Title = $data["title"];

        return $this;
    }
}