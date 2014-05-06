<?php

class SettingsController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        $this->_helper->redirector('index', 'index');
    }

    public function loginAction()
    {
        if ($_POST) {
        	setcookie('bggusername', $this->_getParam('bggusername'), 0, '/');
        	setcookie('bggpassword', $this->_getParam('bggpassword'), 0, '/');
        	$this->_helper->redirector('index', 'index');
        }
    }

    public function logoutAction()
    {
        setcookie ('bggusername', '', time() - 3600);
        setcookie ('bggpassword', '', time() - 3600);
       	$this->_helper->redirector('index', 'index');
    }


}





