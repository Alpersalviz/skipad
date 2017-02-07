<?php
/**
 * Created by PhpStorm.
 * User: AlperSalviz
 * Date: 7.2.2017
 * Time: 22:10
 */

namespace AppBundle\Data\Repository;


use AppBundle\Data\Model\PpcCountry;

class PpcCountryRepository extends BaseRepository
{

    public function GetCountryPpc($country,$is3g){
        try{

            $query="SELECT *
                    From ppc_country
                    WHERE country_code = :country_code AND is_3g = :is_3g";
            $result = $this->getConnection()->prepare($query);

            $result->execute(array(
                ':country_code'     => $country,
                ':is_3g'            => $is3g

            ));

            if( $result === false)
                return false;

            $results = $result->fetchAll();

            $ppcCountry = array();

            foreach ($results as &$result){
                $ppcCountry[$result["ad_type"]] = (new PpcCountry())->MapFrom($result);
            }

            return $ppcCountry;

        }catch (Exception $e){
            return false;
        }

    }

}