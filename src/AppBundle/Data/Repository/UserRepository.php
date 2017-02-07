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

    public function GetUserByType()
    {
        try
        {
            $query = "SELECT *  
                      FROM user
                      WHERE user_type = 'user'";

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
    public function GetUserById($id)
    {
        try
        {
            $query = "SELECT *  
                      FROM user
                      WHERE ID = :id";

            $result = $this->getConnection()->prepare($query);
            $result->execute(array(
                'id' => $id
            ));
            $result = $result->fetch();

            if ($result === false)
                return false;

            $user = (new User())->MapFrom($result);

            return $user;

        }catch (Exception $e)
        {
            return false;
        }
    }

    public function UpdateUserPublish($id,$publish)
    {
        try
        {
            $query = "UPDATE user  
                      SET publish = :publish
                      WHERE ID = :id";

            $result = $this->getConnection()->prepare($query);
            $result->execute(array(
                ':id'       => $id,
                ':publish'  => $publish
            ));


            return $result;

        }catch (Exception $e)
        {
            return false;
        }
    }
    public function AddBalance($userId, $balance){
     try{
        $query="UPDATE user
                SET balance = balance + :balance
                WHERE ID = :id";

        $result = $this->getConnection()->prepare($query);
        $result->execute(array(
            ':balance'  => $balance,
            ':id'       => $userId
        ));

        if ($result === false)
            return false;

         return true;
     }catch (Exception $e){
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

            if ($result === false){
                return array(
                    'user' => false,
                    'message' => "Kullanıcı Adı yada şifre hatalı"
                );
            }else{

                $queryPublish="SELECT *
                    From user WHERE email = :email AND password = :password AND user_type = :user_type AND publish = 1";
                $resultPublish = $this->getConnection()->prepare($queryPublish);

                $resultPublish->execute(array(
                    ':email'        => $email,
                    ':password'     => $password,
                    ':user_type'    => $userType
                ));


                $resultPublish = $resultPublish->fetch();
                if ($resultPublish === false){
                    return array(
                        'user' => false,
                        'message' => "Banlandığınız için giriş yapamazsınız"
                    );
                }

                return array(
                    'user' =>  (new User())->MapFrom($resultPublish),
                    'message' => "Giriş Başarılı"
                );

            }



        }catch (Exception $e){
            return false;
        }

    }



    public function RegisterUser(User $user){

        try{

            $query="INSERT INTO user
                  (email, password, user_type, name, surname, address1, address2, city, country, zip_code, phone_number, account_type, payment_type, payment_info, balance, created_ip,publish)VALUES
                  (:email, :password, :user_type, :name, :surname, :address1, :address2, :city, :country, :zip_code, :phone_number, :account_type, :payment_type, :payment_info, :balance, :created_ip),:publish;";

            $result = $this->getConnection()->prepare($query);
            $result->execute(array(
                ':email'            =>$user->Email,
                ':password'         =>$user->Password,
                ':user_type'        =>$user->UserType,
                ':name'             =>$user->Name,
                ':surname'           =>$user->SurName,
                ':address1'         =>$user->Address1,
                ':address2'         =>$user->Address2,
                ':city'             =>$user->City,
                ':country'          =>$user->Country,
                ':zip_code'         =>$user->ZipCode,
                ':phone_number'     =>$user->PhoneNumber,
                ':account_type'     =>$user->AccountType,
                ':payment_type'     =>$user->PaymentType,
                ':payment_info'     =>$user->PaymentInfo,
                ':balance'          =>0,
                ':created_ip'       =>$user->CreateIp,
                ':publish'          =>1
            ));

            if ($result === false)
                return false;

            return true;


        }catch (Exception $e){
            return false;
        }
    }

    public function UpdateUserByID(User $user){

        try{

            $query="UPDATE user SET
                  email = :email, password = :password , user_type = :user_type, name = :name, surname =:surname, address1 = :address1, address2 = :address2, city = :city, country = :country, zip_code =:zip_code, phone_number = :phone_number, account_type = :account_type, payment_type = :payment_type, payment_info = :payment_info";

            $parameters = array(
                ':id'               =>$user->ID,
                ':email'            =>$user->Email,
                ':password'         =>$user->Password,
                ':user_type'        =>$user->UserType,
                ':name'             =>$user->Name,
                ':surname'          =>$user->SurName,
                ':address1'         =>$user->Address1,
                ':address2'         =>$user->Address2,
                ':city'             =>$user->City,
                ':country'          =>$user->Country,
                ':zip_code'         =>$user->ZipCode,
                ':phone_number'     =>$user->PhoneNumber,
                ':account_type'     =>$user->AccountType,
                ':payment_type'     =>$user->PaymentType,
                ':payment_info'     =>$user->PaymentInfo
            );

            if($user->Balance != null){
                $query .=", balance = :balance";
                $parameters[":balance"] = $user->Balance;

            }
            $query .=" WHERE ID = :id";
            $result = $this->getConnection()->prepare($query);
            $result->execute($parameters);

            if ($result === false)
                return false;

            return true;


        }catch (Exception $e){
            return false;
        }
    }


}