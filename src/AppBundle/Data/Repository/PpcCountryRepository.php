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

    public function GetAllPpc(){
        try{

            $query="SELECT *
                    From ppc_country";
            $result = $this->getConnection()->prepare($query);

            $result->execute();

            if( $result === false)
                return false;

            $results = $result->fetchAll();

            $ppcCountry = array();

            foreach ($results as &$result){
                $ppcCountry[] = (new PpcCountry())->MapFrom($result);
            }

            return $ppcCountry;

        }catch (Exception $e){
            return false;
        }

    }


    public function GetPpcById($id){
        try{

            $query="SELECT *
                    From ppc_country
                  WHERE ID =".$this->getConnection()->quote($id);
            $result = $this->getConnection()->prepare($query);

            $result->execute();

            if( $result === false)
                return false;

            $results = $result->fetch();

                $ppcCountry = (new PpcCountry())->MapFrom($results);


            return $ppcCountry;

        }catch (Exception $e){
            return false;
        }

    }




    public function AddPpcCountry(PpcCountry $ppcCountry){
        try{
            $this->getConnection()->beginTransaction();
            $query="INSERT INTO 
                    ppc_country (ppc, ppc_publisher, country_code, is_3g, ad_type)
                  VALUES (:ppc, :ppc_publisher, :country_code, :is_3g, :ad_type)";


            $result = $this->getConnection()->prepare($query);
            $result->execute(array(
                ':ppc'            => $ppcCountry->Ppc,
                ':ppc_publisher'  => $ppcCountry->PpcPublisher,
                ':country_code'   => $ppcCountry->CountryCode,
                ':is_3g'          => $ppcCountry->Is3g,
                ':ad_type'          => $ppcCountry->AdType
            ));

            $this->getConnection()->commit();

            if ($result === false){
                $this->getConnection()->rollBack();
                return false;

            }

            return true;

        }catch (Exception $e){
            $this->getConnection()->rollBack();
            return false;
        }

    }

    public function UpdatePpcCountry(PpcCountry $ppcCountry){
        try{
            $this->getConnection()->beginTransaction();
            $query="UPDATE ppc_country
                    SET ppc = :ppc, ppc_publisher = :ppc_publisher, country_code = :country_code, is_3g = :is_3g, ad_type =:ad_type
                    WHERE ID =".$this->getConnection()->quote($ppcCountry->ID);


            $result = $this->getConnection()->prepare($query);
            $result->execute(array(
                ':ppc'            => $ppcCountry->Ppc,
                ':ppc_publisher'  => $ppcCountry->PpcPublisher,
                ':country_code'   => $ppcCountry->CountryCode,
                ':is_3g'          => $ppcCountry->Is3g,
                ':ad_type'          => $ppcCountry->AdType
            ));

            $this->getConnection()->commit();

            if ($result === false){
                $this->getConnection()->rollBack();
                return false;

            }

            return true;

        }catch (Exception $e){
            $this->getConnection()->rollBack();
            return false;
        }

    }


    public function DeletePpcCountry($id){
        try{
            $this->getConnection()->beginTransaction();
            $query="DELETE FROM ppc_country
                    WHERE ID =".$this->getConnection()->quote($id);


            $result = $this->getConnection()->prepare($query);
            $result->execute();

            $this->getConnection()->commit();

            if ($result === false){
                $this->getConnection()->rollBack();
                return false;

            }

            return true;

        }catch (Exception $e){
            $this->getConnection()->rollBack();
            return false;
        }

    }

}