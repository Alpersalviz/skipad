<?php
/**
 * Created by PhpStorm.
 * User: AlperSalviz
 * Date: 21.1.2017
 * Time: 20:23
 */

namespace AppBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\Session;

class BaseController extends Controller
{
    private $_session;

    public function Initialize(Session $session)
    {
        $this->_session = $session;

    }

    public function GetSession()
    {
        if(!$this->_session->isStarted())
        {
            $this->_session->start();
        }
        return $this->_session;
    }

}