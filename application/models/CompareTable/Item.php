<?php

class Model_CompareTable_Item
{
	protected $_column;
	protected $_value;
	
	function __construct(array $values = null)
	{
		if ($values !== null) {
			$this->set($values);
		}
	}
	
	public function set(array $values)
	{
		$this->setColumn($values['column']);
		$this->setValue($values['value']);
	}
	
	public function setColumn($column)
	{
		$this->_column = $column;
		return $this;
	}

	public function setValue($value)
	{
		$this->_value = $value;
		return $this;
	}
	
	public function getColumn()
	{
		return $this->_column;
	}
	
	public function getValue()
	{
		return $this->_value;
	}
	

}