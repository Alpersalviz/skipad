<?php
/**
 * Created by PhpStorm.
 * User: AlperSalviz
 * Date: 21.1.2017
 * Time: 20:43
 */

namespace AppBundle\Data\Repository;


use Doctrine\DBAL\Connection;

class BaseRepository
{

    private $_connection;

    public function Initialize(Connection $connection)
    {
         $this->_connection = $connection;

    }

    /**
     * @return \Doctrine\DBAL\Connection
     */
    public function getConnection()
    {
        return $this->_connection;
    }

}