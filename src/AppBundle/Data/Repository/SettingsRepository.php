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
                    From settings
                    WHERE ID = 1";
        $result = $this->getConnection()->prepare($query);

        $result->execute();

        if( $result === false)
            return false;

        $result = $result->fetch();

        $settings = (new Setting())->MapFrom($result);

        return $settings;
    }catch (Exception $e){
        return false;

    }


    }

    public function UpdateSetting(Setting $setting)
    {
        try
        {
            $query = "UPDATE settings SET 
                      payment_info = :payment_info,
                      title = :title,
                      header_ppc = :header_ppc,
                      interstitial_ppc = :interstitial_ppc,
                      popup_ppc = :popup_ppc,
                      payment_banks = :payment_banks,
                      minimum_payment = :minimum_payment,
                      header_ppc_publisher = :header_ppc_publisher,
                      interstitial_ppc_publisher = :interstitial_ppc_publisher,
                      popup_ppc_publisher = :popup_ppc_publisher
                     WHERE 1";

            $result = $this->getConnection()->prepare($query);
            $result->execute(array(
                ':payment_info'                 => $setting->PaymentInfo,
                ':title'                        => $setting->Title,
                ':header_ppc'                   => $setting->HeaderPpc,
                ':interstitial_ppc'             => $setting->InterstitialPpc,
                ':popup_ppc'                    => $setting->PopupPpc,
                ':payment_banks'                => $setting->PaymentBanks,
                ':minimum_payment'              => $setting->MinimumPayment,
                ':header_ppc_publisher'         => $setting->HeaderPpcPublisher,
                ':interstitial_ppc_publisher'   => $setting->InterstitialPpcPublisher,
                ':popup_ppc_publisher'          => $setting->PopupPpcPublisher
            ));

            return $result;

        }catch (Exception $e)
        {
            return false;
        }
    }



}