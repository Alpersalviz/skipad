<?php
/**
 * Created by PhpStorm.
 * User: AlperSalviz
 * Date: 2.2.2017
 * Time: 16:24
 */

namespace AppBundle\Data\Repository;


use AppBundle\Data\Model\Setting;
use Symfony\Component\Config\Definition\Exception\Exception;

class SettingsRepository extends BaseRepository
{

    public function GetSetting(){

    try{
        $query="SELECT *
                    From settings";
        $result = $this->getConnection()->prepare($query);

        $result->execute();

        if( $result === false)
            return false;

        $results = $result->fetchAll();

        $settings = array();

        foreach ($results as &$result){
            $settings[] = (new Setting())->MapFrom($result);
        }

        return $settings;
    }catch (Exception $e){
        return false;
        
    }


    }

}