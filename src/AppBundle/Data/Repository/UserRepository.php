<?php

namespace AppBundle\Data\Repository;


use AppBundle\Data\Model\User;
use Symfony\Component\Config\Definition\Exception\Exception;

class UserRepository extends BaseRepository
{
    public function GetUser()
    {
        try
        {
            $query = "SELECT *  
                      FROM user";

            $result = $this->getConnection()->prepare($query);
            $result->execute();
            $results = $result->fetchAll();

            if ($result === false)
                return false;

            $users = [];

            foreach ($results as &$result)
            {
                $users[] = (new User())->MapFrom($result);
            }

            return $users;

        }catch (Exception $e)
        {
            return false;
        }
    }

    public function LoginUser($email,$password,$userType){

        try{

            $query="SELECT *
                    From user WHERE email = :email AND password = :password AND user_type = :user_type";

            $result = $this->getConnection()->prepare($query);
            $result->execute(array(
                ':email'        => $email,
                ':password'     => $password,
                ':user_type'    => $userType
            ));

           $result = $result->fetch();

            if ($result === false)
                return false;

            return (new User())->MapFrom($result);

        }catch (Exception $e){
            return false;
        }

    }


}