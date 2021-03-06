<?php
/**
 * Created by PhpStorm.
 * User: AlperSalviz
 * Date: 30.1.2017
 * Time: 13:55
 */

namespace AppBundle\Data\Repository;


use AppBundle\Data\Model\Url;
use AppBundle\Domain\Model\PagedList;
use Symfony\Component\Config\Definition\Exception\Exception;

class UrlRepository extends BaseRepository
{

    public function GetByUserIdUrl($userId,$type){

        try{
            $query="SELECT *
                    FROM urls 
                    WHERE user_id = :user_id AND type = :type
                    ORDER BY created_date DESC";
            $result = $this->getConnection()->prepare($query);
            $result->execute(array(
                ':user_id' => $userId,
                ':type' => $type
            ));
            $results = $result->fetchAll();

            $urls = array();

            foreach ($results as $result){
                $urls[] = (new Url())->MapFrom($result);
            }

            return $urls;


        }catch (Exception $e){
            return false;
        }
    }

    public function GetUrlById($id){

        try{
            $query="SELECT *
                    FROM urls 
                    WHERE ID = :id";
            $result = $this->getConnection()->prepare($query);
            $result->execute(array(
                ':id' => $id
            ));
            $result = $result->fetch();

                $urls = (new Url())->MapFrom($result);


            return $urls;


        }catch (Exception $e){
            return false;
        }
    }

    public function GetUrl(){

        try{
            $query="SELECT *
                    FROM urls  
                    ORDER BY created_date DESC";
            $result = $this->getConnection()->prepare($query);
            $result->execute();
            $results = $result->fetchAll();

            $urls = array();

            foreach ($results as $result){
                $urls[] = (new Url())->MapFrom($result);
            }

            return $urls;


        }catch (Exception $e){
            return false;
        }
    }

    public function GetUrlUser(){

        try{
            $query="SELECT ur.*,u.email
                    FROM urls ur
                    INNER JOIN user u
                    ON ur.user_id = u.ID
                    ORDER BY created_date DESC";
            $result = $this->getConnection()->prepare($query);
            $result->execute();
            $results = $result->fetchAll();

            $urls = array();

            foreach ($results as $result){
                $urls[] = (new Url())->MapFrom($result);
            }

            return $urls;


        }catch (Exception $e){
            return false;
        }
    }
    public function AddUrl($redirectUrl,$randomUrl,$userId,$type){

    try{

        $query="INSERT INTO urls
                  (url,redirect_url, price, impression, user_id, created_date,type)VALUES
                  (:url, :redirect_url, :price, :impression, :user_id, :created_date,:type);";

        $result = $this->getConnection()->prepare($query);
        $result->execute(array(
            ':url'              =>$randomUrl,
            ':redirect_url'     =>$redirectUrl,
            ':price'            =>0,
            ':impression'       =>0,
            ':user_id'          =>$userId,
            ':created_date'     =>date("Y-m-d H:i:s"),
            ':type'             =>$type
        ));

        if ($result === false)
            return array(
                'succsess' => false,
                'id' => null
            );

        $lastId = intval($this->getConnection()->lastInsertId());

        return array(
            'succsess' => true,
            'id' => $lastId
        );


    }catch (Exception $e){
        return false;
    }
}


    public function UpdateUrl($id ,$redirectUrl){

        try{

            $query="UPDATE urls
                    SET redirect_url = :redirect_url
                    WHERE ID = :id";

            $result = $this->getConnection()->prepare($query);
            $result->execute(array(
                ':id'               =>$id,
                ':redirect_url'     =>$redirectUrl
            ));

            if ($result === false)
                return false;

            return true;


        }catch (Exception $e){
            return false;
        }
    }


    public function GetRandomUrl($url){
        try{
            $query="SELECT COUNT(url) as count
                    FROM urls
                    WHERE url = :url";

            $result = $this->getConnection()->prepare($query);
            $result->execute(array(
                ':url' => $url
            ));
            $results = $result->fetch();

            if ($results === false)
                return false;

            $list = new PagedList();

            if((int)$results['count'] == 0){
                $list = new PagedList(null,0);
            }else{
                $list = new PagedList(null,(int)$results['count']);
            }


            return $list;

        }catch (Exception $e){
            return false;
        }
    }

    public function GetByRedirectUrl($url)
    {
        try {
            $query = "SELECT *
                    FROM urls 
                    WHERE url = :url";
            $result = $this->getConnection()->prepare($query);
            $result->execute(array(
                ':url' => $url
            ));
            $result = $result->fetch();

            if ($result === false)
                return false;

            return (new Url())->MapFrom($result);


        } catch (Exception $e) {
            return false;
        }
    }


    public function DeleteUrl($id)
    {
        try {

            $query = 'DELETE 
                  FROM urls
                  WHERE ID = '.$id;

            $result = $this->getConnection()->prepare($query);
            $result->execute();

            if($result === false)
                return false;

            return true;

        } catch (Exception $e) {
            return false;
        }
    }

    public function UpdateVisitor($urlId,$price)
    {
        try{

            $query="UPDATE urls 
                    SET impression = impression +1 , visitor = visitor +1 , price = price + :price
                    WHERE ID = :id";


            $result = $this->getConnection()->prepare($query);
            $result->execute(array(
                ':price'  =>$price,
                ':id'     =>$urlId,
            ));

            return $result;


        }catch (Exception $e){
            return false;
        }
    }

    public function UpdateImpression($urlId)
    {
        try{

            $query="UPDATE urls 
                    SET impression = impression +1
                    WHERE ID = :id";


            $result = $this->getConnection()->prepare($query);
            $result->execute(array(
                ':id'     =>$urlId
            ));

            return $result;


        }catch (Exception $e){
            return false;
        }

    }

}