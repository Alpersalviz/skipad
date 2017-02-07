<?php
/**
 * Created by PhpStorm.
 * User: AlperSalviz
 * Date: 30.1.2017
 * Time: 20:57
 */

namespace AppBundle\Data\Repository;


use AppBundle\Controller\BaseController;
use AppBundle\Data\Model\Ads;
use Symfony\Component\Config\Definition\Exception\Exception;

class AdvertiserRepository extends BaseRepository
{
    private $_userRepository;

    public function __construct(UserRepository $userrepository)
    {
        $this->_userRepository = $userrepository;

    }

    public function GetAds(){
        try{

            $query="SELECT *
                    From ads
                    ORDER By created_date";
            $result = $this->getConnection()->prepare($query);

            $result->execute();

            if( $result === false)
                return false;

            $results = $result->fetchAll();

            $ads = array();

            foreach ($results as &$result){
                $ads[] = (new Ads())->MapFrom($result);
            }

            return $ads;

        }catch (Exception $e){
            return false;
        }
    }


    public function GetAdsByType($type){
        try{

            $query="SELECT ID,url
                    From ads
                    WHERE ad_type = :ad_type AND current_price > 0 AND publish = 1
                    ORDER BY RAND() 
                    LIMIT 1";

            $result = $this->getConnection()->prepare($query);

            $result->execute(array(
                ':ad_type' => $type
            ));

            if( $result === false)
                return false;

            $result = $result->fetch();
            if($result === false){
                return array(
                    'ID' => 0,
                    'url' => '/'
                );
            }
            return $result;

        }catch (Exception $e){
            return false;
        }
    }

    public function GetAdsById($id){
        try{

            $query="SELECT *
                    From ads
                    WHERE ID = :id";
            $result = $this->getConnection()->prepare($query);

            $result->execute(array(
                ':id' => $id
            ));

            if( $result === false)
                return false;

            $result = $result->fetch();

            $ad = (new Ads())->MapFrom($result);


            return $ad;

        }catch (Exception $e){
            return false;
        }
    }
    public function GetAdsByUserId($id){
        try{

            $query="SELECT *
                    From ads
                    WHERE user_id = :user_id AND publish = 1";
            $result = $this->getConnection()->prepare($query);

            $result->execute(array(
                ':user_id' => $id
            ));

            if( $result === false)
                return false;

            $results = $result->fetchAll();

            $ads = array();

            foreach ($results as &$result){
                $ads[] = (new Ads())->MapFrom($result);
            }

            return $ads;

        }catch (Exception $e){
            return false;
        }
    }
    public function AddAd(Ads $ads){
        try{
            $this->getConnection()->beginTransaction();
            $query="INSERT INTO 
                    ads (title, url, ad_type, impression, user_id, created_date, created_ip, ppc, first_price, current_price, publish)
                  VALUES (:title, :url, :ad_type, :impression, :user_id, :created_date, :created_ip, :ppc, :first_price, :current_price, :publish)";

            $this->_userRepository->AddBalance($ads->UserId,$ads->FirstPrice * -1 );

            $result = $this->getConnection()->prepare($query);
            $result->execute(array(
                ':title'            => $ads->Title,
                ':url'              => $ads->Url,
                ':ad_type'          => $ads->AdType,
                ':impression'       => $ads->Impression,
                ':user_id'          => $ads->UserId,
                ':created_date'     => $ads->CreatedDate,
                ':created_ip'       => $ads->CreatedIp,
                ':ppc'              => $ads->Ppc,
                ':first_price'      => $ads->FirstPrice,
                ':current_price'    => $ads->CurrentPrice,
                ':publish'          => $ads->Publish
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


    public function UpdateAdd($id ,$url ,$title){

        try{

            $query="UPDATE ads 
                    SET title = :title , url = :url
                    WHERE ID = :id";


            $result = $this->getConnection()->prepare($query);
            $result->execute(array(
                ':id'               =>$id,
                ':url'              =>$url,
                ':title'            =>$title
            ));

            if ($result === false)
                return false;

            return true;


        }catch (Exception $e){
            return false;
        }
    }

    public function UpdateAddByAdmin($id ,$url ,$title,$ppc){

        try{

            $query="UPDATE ads 
                    SET title = :title , url = :url , ppc = :ppc
                    WHERE ID = :id";


            $result = $this->getConnection()->prepare($query);
            $result->execute(array(
                ':id'               =>$id,
                ':url'              =>$url,
                ':title'            =>$title,
                ':ppc'              =>$ppc
            ));

            if ($result === false)
                return false;

            return true;


        }catch (Exception $e){
            return false;
        }
    }

    public function CancelAdd($id,$publish = 0){

        try{

            $query="UPDATE ads 
                    SET publish = :publish
                    WHERE ID = :id";


            $result = $this->getConnection()->prepare($query);
            $result->execute(array(
                ':id'               =>$id,
                ':publish'          =>$publish,
            ));

            if ($result === false)
                return false;

            return true;


        }catch (Exception $e){
            return false;
        }
    }

    public function UpdateImpression($id){

        try{

            $query="UPDATE ads 
                    SET impression = impression +1 , current_price = current_price - ppc
                    WHERE ID = :id";


            $result = $this->getConnection()->prepare($query);
            $result->execute(array(
                ':id'               =>$id,
            ));

            return $result;


        }catch (Exception $e){
            return false;
        }
    }




}