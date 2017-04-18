<?php
/**
 * Created by PhpStorm.
 * User: AlperSalviz
 * Date: 17.4.2017
 * Time: 13:31
 */

namespace AppBundle\Data\Model;


class Language
{
    public $ID;
    public $Code;

    public function MapFrom($data){
        $this->ID = $data["ID"];
        $this->Code = $data["code"];

        return $this;
    }

}