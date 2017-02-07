<?php
/**
 * Created by PhpStorm.
 * User: AlperSalviz
 * Date: 1.2.2017
 * Time: 22:48
 */

namespace AppBundle\Data\Repository;


use AppBundle\Data\Model\Payment;
use AppBundle\Data\Model\PaymentUser;
use Symfony\Component\Config\Definition\Exception\Exception;

class PaymentRepository extends BaseRepository
{
    public function AddPayment(Payment $payment){

        try{
            $this->getConnection()->beginTransaction();

            if($payment->Type == 'send'){
                    $queryUser="UPDATE user 
                        SET balance = 0
                        WHERE ID = :id";



                $resultUser = $this->getConnection()->prepare($queryUser);
                $resultUser->execute(array(
                    ':id'              =>$payment->UserId
                ));

                if ($resultUser === false){
                    $this->getConnection()->rollBack();
                    return false;
                }
            }

            $query="INSERT INTO payments
                  (user_id,payment_type, status, balance, payment_info , type,payment_date)VALUES
                  (:user_id, :payment_type, :status, :balance, :payment_info , :type ,:payment_date);";

            $result = $this->getConnection()->prepare($query);
            $result->execute(array(
                ':user_id'              =>$payment->UserId,
                ':payment_type'         =>$payment->PaymentType,
                ':status'               =>$payment->Status,
                ':balance'              =>$payment->Balance,
                ':payment_info'         =>$payment->PaymentInfo,
                ':payment_date'         => date("Y-m-d H:i:s"),
                ':type'                 =>$payment->Type
            ));

            if ($result === false){
                $this->getConnection()->rollBack();
                return false;
            }

            $this->getConnection()->commit();

            return true;

        }catch (Exception $e){
            $this->getConnection()->rollBack();
            return false;
        }
    }

    public function GetPayments($type,$userId=0){

        try{
            $query="SELECT * 
                    FROM payments 
                    WHERE type = :type";
            $parameters = array(':type' =>  $type);

            /*Userid 0 ise tümü gelsin, yoksa o userinkiler*/
            if($userId != 0){
                $query .=" and user_id = :userId";
                $parameters[":userId"] =$userId;
            }
            $query .= " ORDER BY status ASC";
            $results = $this->getConnection()->prepare($query);
            $results->execute($parameters);

            if( $results === false)
                return false;

            $results = $results->fetchAll();

            $payments = array();

            foreach ($results as &$result){
                $payments[] = (new Payment())->MapFrom($result);
            }

            return $payments;

        }catch (Exception $e){
            return false;
        }
    }

    public function GetUserPayments($type){

        try{
            $query="SELECT 
                        u.email,
                        u.name,
                        u.surname,
                        u.ID as user_id,
                        u.phone_number,
                        p.balance,
                        p.payment_date,
                        p.payment_info,
                        p.status,
                        p.ID as payment_id
                    FROM payments  p
                    INNER JOIN user u
                    ON u.ID = p.user_id
                    WHERE p.type = :type
                    ORDER BY(CASE p.status
 			  			WHEN 'wait' 	 THEN 1
  					  	WHEN 'done' 	 THEN 2  
   					 ELSE 100 END) ASC,  p.payment_date ASC  ";


            $results = $this->getConnection()->prepare($query);
            $results->execute(array(
                ':type' => $type
            ));

            if( $results === false)
                return false;

            $results = $results->fetchAll();

            $payments = array();

            foreach ($results as &$result){
                $userpayments[] = (new PaymentUser())->MapFrom($result);
            }

            return $userpayments;

        }catch (Exception $e){
            return false;
        }
    }

    public function UpdateStatus($id)
    {
        try
        {
            $query = "UPDATE payments  
                      SET status = 'done'
                      WHERE ID = :id";

            $result = $this->getConnection()->prepare($query);
            $result->execute(array(
                ':id'       => $id
            )); 

            return $result;

        }catch (Exception $e)
        {
            return false;
        }
    }

    public function SetPayment($userId,$balance)
    {
        try
        {
            $query = "UPDATE user  
                      SET balance = balance + :balance
                      WHERE ID = :id";

            $result = $this->getConnection()->prepare($query);
            $result->execute(array(
                ':id'       =>$userId,
                ':balance'  =>(float)$balance
            ));

            return $result;

        }catch (Exception $e)
        {
            return false;
        }

    }


}