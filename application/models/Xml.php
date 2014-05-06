<?php

abstract class Model_Xml implements ArrayAccess
{
	protected $_data;

	protected $_url = null;

	function __construct(array $values = null)
	{
		if ($values !== null) {
			$this->load($values);
		}
	}

	public function load(array $values = array())
	{
		if ($this->_url === null) {
			throw new Zend_Exception('Url is not defined in class');
		}
		$url = $this->_url;
		foreach ($values as $key => $value) {
			$url = preg_replace("/\{$key\}/", urlencode($value), $url);
		}
		$client = new Zend_Http_Client($url);
		$xml = $client->request()->getBody();
		if (!preg_match('/^\s*<\?xml/', $xml)) {
			$xml = "<?xml version=\"1.0\"?>\n" . $xml;
		}
		$this->_data = new SimpleXMLElement($xml);
	}

	public function data()
	{
		return $this->_data;
	}

	public function offsetSet($offset, $value)
	{
		$this->_data[$offset] = $value;
	}
	
	public function offsetExists($offset)
	{
		return isset($this->_data[$offset]);
	}
	
	public function offsetUnset($offset)
	{
		unset($this->_data[$offset]);
	}
	
	public function offsetGet($offset)
	{
		return isset($this->_data[$offset]) ? $this->_data[$offset] : null;
	}
	
	public function __get($name)
	{
		return $this->_data->$name;
	}
	
}