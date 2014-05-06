<?php

class SearchController extends Zend_Controller_Action
{

	public function init()
	{
		/* Initialize action controller here */
	}

	public function indexAction()
	{
		if (!$key = $this->_getParam('search')) {
			$this->_helper->redirector('index', 'index');
		}
		$exact = $this->_getParam('exact');
		$results = new Model_Search(array('key' => $key, 'exact' => $exact));
		$this->view->results = $results;
		$this->view->search = $key;
		$this->view->exact = ($exact == '1');
		$this->view->objectId = $this->_getParam('objectid');
	}

}

