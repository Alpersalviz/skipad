<?php
/**
 * Created by PhpStorm.
 * User: AlperSalviz
 * Date: 17.4.2017
 * Time: 13:32
 */

namespace AppBundle\Data\Model;


class Label
{
    public $ID;
    public $LabelCode;
    public $Label;
    public $LanguageId;

    public function MapFrom($data){
        $this->ID = $data["ID"];
        $this->LabelCode = $data["label_code"];
        $this->Label = $data["label"];
        $this->LanguageId = $data["language_id"];

        return $this;
    }
}