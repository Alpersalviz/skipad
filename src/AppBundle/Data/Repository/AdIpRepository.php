<?php
/**
 * Created by PhpStorm.
 * User: AlperSalviz
 * Date: 4.2.2017
 * Time: 17:01
 */

namespace AppBundle\Data\Repository;


use AppBundle\Data\Model\AdIp;
use Symfony\Component\Config\Definition\Exception\Exception;

class AdIpRepository extends BaseRepository
{

    public function GetAdCountByIp($ip,$urlId,$adType){

        try{
            $query="SELECT COUNT(ID) as count
                    FROM ad_ip
                    WHERE  ip = :ip AND url_id = :url_id AND ad_type = :ad_type";

            $result = $this->getConnection()->prepare($query);
            $result->execute(array(
                ':ip'       => $ip,
                ':url_id'   => $urlId,
                ':ad_type'  => $adType
            ));

            $result = $result->fetchColumn();


            return $result;

        }catch (Exception $e){

        }
    }


    public function AddAdIp(AdIp $adIp){

        try{
            $query="INSERT INTO ad_ip(ad_id, ip, date, url_id, ad_type) VALUES (:ad_id, :ip, :date, :url_id, :ad_type)";

            $result = $this->getConnection()->prepare($query);
            $result->execute(array(
                ':ad_id'        => $adIp->AdId,
                ':ip'           => $adIp->Ip,
                ':date'         => date("Y-m-d H:i:s"),
                ':url_id'       => $adIp->UrlId,
                ':ad_type'      => $adIp->AdType
            ));


            return $result;

        }catch (Exception $e){

        }

    }

}