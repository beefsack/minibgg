<?php

class BoardgameController extends Zend_Controller_Action
{

	public function init()
	{
		/* Initialize action controller here */
	}

	public function indexAction()
	{
		$this->_helper->redirector('index', 'index');
	}
	
	public function singleAction()
	{
		if (!$objectId = $this->_getParam('objectid')) {
			$this->_helper->redirector('index', 'index');
		}
		$data = new Model_Boardgame(array('objectid' => $objectId));
		$this->view->data = $data;
	}
	
	public function multipleAction()
	{
		if (!$objectId = $this->_getParam('objectid')) {
			$this->_helper->redirector('index', 'index');
		}
		$objectIds = array_unique(explode(',', $objectId));
		$data = new Model_Boardgame(array('objectid' => $objectId));
		$shortnames = array();
		foreach ($data->boardgame as $boardgame) {
			$shortname = $this->getShortName($boardgame->name[0]);
			while (in_array($shortname, $shortnames)) {
				$shortname .= '*';
			}
			$shortnames[] = $shortname;
			$boardgame->shortname = $shortname;
			$removeIds = array();
			foreach ($objectIds as $id) {
				if ($id != $boardgame['objectid']) {
					$removeIds[] = $id;
				}
			}
			$boardgame->removeids = implode(',', $removeIds);
		}
		// Build compare table
		$compareTable = new Model_CompareTable();
		$compareTable->setColumns($shortnames);
		// Rank
		$row = new Model_CompareTable_Row();
		$rowData = array();
		foreach ($data->boardgame as $boardgame) {
			$rowData[] = array('column' => $boardgame->shortname, 'value' => (int) $boardgame->statistics->ratings->ranks[0]->rank);
		}
		$row->addItems($rowData)
			->setHighlight(new Model_CompareTable_ItemSearch_LowestIgnoreZero())
			->setTitle('Rank');
		$compareTable->addRow($row);
		// Rating
		$row = new Model_CompareTable_Row();
		$rowData = array();
		foreach ($data->boardgame as $boardgame) {
			$rowData[] = array('column' => $boardgame->shortname, 'value' => round((float) $boardgame->statistics->ratings->average, 2));
		}
		$row->addItems($rowData)
			->setHighlight(new Model_CompareTable_ItemSearch_Highest())
			->setTitle('Average');
		$compareTable->addRow($row);
		// Bayesian
		$row = new Model_CompareTable_Row();
		$rowData = array();
		foreach ($data->boardgame as $boardgame) {
			$rowData[] = array('column' => $boardgame->shortname, 'value' => round((float) $boardgame->statistics->ratings->bayesaverage, 2));
		}
		$row->addItems($rowData)
			->setHighlight(new Model_CompareTable_ItemSearch_Highest())
			->setTitle('Bayes Ave.');
		$compareTable->addRow($row);
		// Weight
		$row = new Model_CompareTable_Row();
		$rowData = array();
		foreach ($data->boardgame as $boardgame) {
			$rowData[] = array('column' => $boardgame->shortname, 'value' => round((float) $boardgame->statistics->ratings->averageweight, 2));
		}
		$row->addItems($rowData)
			->setTitle('Weight');
		$compareTable->addRow($row);
		// Owned
		$row = new Model_CompareTable_Row();
		$rowData = array();
		foreach ($data->boardgame as $boardgame) {
			$rowData[] = array('column' => $boardgame->shortname, 'value' => (int) $boardgame->statistics->ratings->owned);
		}
		$row->addItems($rowData)
			->setHighlight(new Model_CompareTable_ItemSearch_Highest())
			->setTitle('Owned');
		$compareTable->addRow($row);
		// Min Players
		$row = new Model_CompareTable_Row();
		$rowData = array();
		foreach ($data->boardgame as $boardgame) {
			$rowData[] = array('column' => $boardgame->shortname, 'value' => (int) $boardgame->minplayers);
		}
		$row->addItems($rowData)
			->setHighlight(new Model_CompareTable_ItemSearch_LowestIgnoreZero())
			->setTitle('Min. Players');
		$compareTable->addRow($row);
		// Max Players
		$row = new Model_CompareTable_Row();
		$rowData = array();
		foreach ($data->boardgame as $boardgame) {
			$rowData[] = array('column' => $boardgame->shortname, 'value' => (int) $boardgame->maxplayers);
		}
		$row->addItems($rowData)
			->setHighlight(new Model_CompareTable_ItemSearch_Highest())
			->setTitle('Max. Players');
		$compareTable->addRow($row);
		// Player Range
		$row = new Model_CompareTable_Row();
		$rowData = array();
		foreach ($data->boardgame as $boardgame) {
			$rowData[] = array('column' => $boardgame->shortname, 'value' => (int) $boardgame->maxplayers - (int) $boardgame->minplayers);
		}
		$row->addItems($rowData)
			->setHighlight(new Model_CompareTable_ItemSearch_Highest())
			->setTitle('Player Range');
		$compareTable->addRow($row);
		// Published
		$row = new Model_CompareTable_Row();
		$rowData = array();
		foreach ($data->boardgame as $boardgame) {
			$rowData[] = array('column' => $boardgame->shortname, 'value' => (int) $boardgame->yearpublished);
		}
		$row->addItems($rowData)
			->setTitle('Published');
		$compareTable->addRow($row);
		// Expansions
		$row = new Model_CompareTable_Row();
		$rowData = array();
		foreach ($data->boardgame as $boardgame) {
			$expansions = 0;
			foreach ($boardgame->boardgameexpansion as $expansion) {
				$expansions++;
			}
			$rowData[] = array('column' => $boardgame->shortname, 'value' => $expansions);
		}
		$row->addItems($rowData)
			->setHighlight(new Model_CompareTable_ItemSearch_Highest())
			->setTitle('Expansions');
		$compareTable->addRow($row);
		// Play Time
		$row = new Model_CompareTable_Row();
		$rowData = array();
		foreach ($data->boardgame as $boardgame) {
			$rowData[] = array('column' => $boardgame->shortname, 'value' => (int) $boardgame->playingtime);
		}
		$row->addItems($rowData)
			->setHighlight(new Model_CompareTable_ItemSearch_LowestIgnoreZero())
			->setTitle('Play Time');
		$compareTable->addRow($row);
		// Age
		$row = new Model_CompareTable_Row();
		$rowData = array();
		foreach ($data->boardgame as $boardgame) {
			$rowData[] = array('column' => $boardgame->shortname, 'value' => (int) $boardgame->age);
		}
		$row->addItems($rowData)
			->setHighlight(new Model_CompareTable_ItemSearch_LowestIgnoreZero())
			->setTitle('Min. Age');
		$compareTable->addRow($row);
		// Trading
		$row = new Model_CompareTable_Row();
		$rowData = array();
		foreach ($data->boardgame as $boardgame) {
			$rowData[] = array('column' => $boardgame->shortname, 'value' => (int) $boardgame->statistics->ratings->trading);
		}
		$row->addItems($rowData)
			->setHighlight(new Model_CompareTable_ItemSearch_Highest())
			->setTitle('Trading');
		$compareTable->addRow($row);
		// Wanting
		$row = new Model_CompareTable_Row();
		$rowData = array();
		foreach ($data->boardgame as $boardgame) {
			$rowData[] = array('column' => $boardgame->shortname, 'value' => (int) $boardgame->statistics->ratings->wanting);
		}
		$row->addItems($rowData)
			->setHighlight(new Model_CompareTable_ItemSearch_Highest())
			->setTitle('Wanting');
		$compareTable->addRow($row);
		// Wishing
		$row = new Model_CompareTable_Row();
		$rowData = array();
		foreach ($data->boardgame as $boardgame) {
			$rowData[] = array('column' => $boardgame->shortname, 'value' => (int) $boardgame->statistics->ratings->wishing);
		}
		$row->addItems($rowData)
			->setHighlight(new Model_CompareTable_ItemSearch_Highest())
			->setTitle('Wishing');
		$compareTable->addRow($row);
		// Want:Trade
		$row = new Model_CompareTable_Row();
		$rowData = array();
		foreach ($data->boardgame as $boardgame) {
			$rowData[] = array('column' => $boardgame->shortname, 'value' => round((float) $boardgame->statistics->ratings->wanting / (float) $boardgame->statistics->ratings->trading, 2));
		}
		$row->addItems($rowData)
			->setHighlight(new Model_CompareTable_ItemSearch_Highest())
			->setTitle('Want:Trade');
		$compareTable->addRow($row);
		// Own:Trade
		$row = new Model_CompareTable_Row();
		$rowData = array();
		foreach ($data->boardgame as $boardgame) {
			$rowData[] = array('column' => $boardgame->shortname, 'value' => round((float) $boardgame->statistics->ratings->owned / (float) $boardgame->statistics->ratings->trading, 2));
		}
		$row->addItems($rowData)
			->setHighlight(new Model_CompareTable_ItemSearch_Highest())
			->setTitle('Own:Trade');
		$compareTable->addRow($row);
		// Ratings
		$row = new Model_CompareTable_Row();
		$rowData = array();
		foreach ($data->boardgame as $boardgame) {
			$rowData[] = array('column' => $boardgame->shortname, 'value' => (int) $boardgame->statistics->ratings->usersrated);
		}
		$row->addItems($rowData)
			->setHighlight(new Model_CompareTable_ItemSearch_Highest())
			->setTitle('Ratings');
		$compareTable->addRow($row);
		// Standard deviation
		$row = new Model_CompareTable_Row();
		$rowData = array();
		foreach ($data->boardgame as $boardgame) {
			$rowData[] = array('column' => $boardgame->shortname, 'value' => round((float) $boardgame->statistics->ratings->stddev, 2));
		}
		$row->addItems($rowData)
			->setHighlight(new Model_CompareTable_ItemSearch_LowestIgnoreZero())
			->setTitle('Std. Dev.');
		$compareTable->addRow($row);
		// Pass values to the view
		$this->view->data = $data;
		$this->view->objectId = implode(',', $objectIds);
		$this->view->compareTable = $compareTable;
	}

	protected function getShortName($name) {
		$namearray = explode(" ", $name);
		$returnstr = "";
		foreach ($namearray as $item) {
			$returnstr .= substr($item, 0, 1);
		}
		return $returnstr;
	}
	
	public function __call($name, $arguments)
	{
		if (!$objectIds = urldecode($this->_getParam('action'))) {
			$this->_helper->redirector('index', 'index');
		}
		$this->getRequest()->setParam('objectid', $objectIds);
		$objectIds = urldecode($this->_getParam('action'));
		$objectIdArray = explode(',', $objectIds);
		if (count($objectIdArray) == 1) {
			$this->singleAction();
			return $this->render('single');
		} else {
			$this->multipleAction();
			return $this->render('multiple');
		}
	}
	
	public function logAction()
	{
		if (!$_COOKIE['bggusername']) $this->_helper->redirector('login', 'settings');
		if (!$objectId = $this->_getParam('objectid')) $this->_helper->redirector('index', 'index');
		if ($_POST) {
			$url = 'http://www.boardgamegeek.com/geekplay.php';
			$client = new Zend_Http_Client($url);
			$client->setParameterPost(array(
				'ajax' => '1',
				'action' => 'save',
				'version' => '2',
				'objecttype' => 'thing',
				'objectid' => $objectId,
				'playdate' => $this->_getParam('dateyear').'-'.$this->_getParam('datemonth').'-'.$this->_getParam('dateday'),
				'dateinput' => date('Y-m-d'),
				'quantity' => $this->_getParam('quantity'),
			));
			$client->setCookie('bggusername', $_COOKIE['bggusername']);
			$client->setCookie('bggpassword', $_COOKIE['bggpassword']);
			$response = $client->request(Zend_Http_Client::POST);
			$this->view->requestSent = true;
			$body = $response->getBody();
			if (preg_match('/Plays:/', $body)) {
				$status = 'Play was successfully logged.';
			} else {
				$status = '<strong>Error logging play:</strong> '.$body;
			}
			$this->view->status = $status;
		} else {
			
		}
		$this->view->objectId = $objectId;
		$this->view->objectName = $this->_getParam('objectname');
	}


}

