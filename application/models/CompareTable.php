<?php

class Model_CompareTable implements ArrayAccess, Iterator
{
	protected $_columns = array();
	protected $_rows = array();

	public function setColumns(array $columns)
	{
		$this->_columns = $columns;
	}

	public function addRow(Model_CompareTable_Row $row)
	{
		$this->_rows[] = $row;
	}

	public function getColumns()
	{
		return $this->_columns;
	}

	public function getRows()
	{
		return $this->_rows;
	}
	
	public function current()
	{
		return current($this->_rows);
	}

	public function key ()
	{
		return key($this->_rows);
	}

	public function next()
	{
		return next($this->_rows);
	}

	public function rewind()
	{
		return reset($this->_rows);
	}

	public function valid()
	{
		return key($this->_rows) !== null;
	}

	public function offsetSet($offset, $value)
	{
		throw new Zend_Exception('Unable to set rows via array interface');
	}

	public function offsetExists($offset)
	{
		return isset($this->_rows[$offset]);
	}

	public function offsetUnset($offset)
	{
		throw new Zend_Exception('Unable to unset rows via array interface');
	}

	public function offsetGet($offset)
	{
		return isset($this->_rows[$offset]) ? $this->_rows[$offset] : null;
	}
}