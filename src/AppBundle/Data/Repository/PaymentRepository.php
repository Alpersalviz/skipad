<?php
/**
 * Created by PhpStorm.
 * User: AlperSalviz
 * Date: 1.2.2017
 * Time: 22:48
 */

namespace AppBundle\Data\Repository;


use AppBundle\Data\Model\Payment;

class PaymentRepository extends BaseRepository
{
    public function AddPayment(Payment $payment){

        try{
            $this->getConnection()->beginTransaction();

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

            $query="INSERT INTO payments
                  (user_id,payment_type, status, balance, payment_info)VALUES
                  (:user_id, :payment_type, :status, :balance, :payment_info);";

            $result = $this->getConnection()->prepare($query);
            $result->execute(array(
                ':user_id'              =>$payment->UserId,
                ':payment_type'         =>$payment->PaymentType,
                ':status'               =>$payment->Status,
                ':balance'              =>$payment->Balance,
                ':payment_info'         =>$payment->PaymentInfo
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

}