<?php

class Model_CompareTable_Row implements ArrayAccess, Iterator
{
	protected $_items = array();
	protected $_highlight = null;
	protected $_title = '';
	
	public function addItem($item)
	{
		if (is_array($item)) {
			$itemObj = new Model_CompareTable_Item($item);
		} elseif ($item instanceof Model_CompareTable_Item) {
			$itemObj = $item;
		} else {
			throw new Zend_Exception('addItem requires an array or Model_CompareTable_Item');
		}
		$this->_items[(string) $itemObj->getColumn()] = $itemObj;
		return $this;
	}
	
	public function addItems(array $items)
	{
		foreach ($items as $item) {
			$this->addItem($item);
		}
		return $this;
	}
	
	public function setTitle($title)
	{
		$this->_title = $title;
		return $this;
	}
	
	public function getTitle()
	{
		return $this->_title;
	}
	
	public function current()
	{
		return current($this->_items);
	}

	public function key ()
	{
		return key($this->_items);
	}

	public function next()
	{
		return next($this->_items);
	}

	public function rewind()
	{
		return reset($this->_items);
	}

	public function valid()
	{
		return key($this->_items) !== null;
	}

	public function offsetSet($offset, $value)
	{
		throw new Zend_Exception('Unable to set items via array interface');
	}

	public function offsetExists($offset)
	{
		return isset($this->_items[$offset]);
	}

	public function offsetUnset($offset)
	{
		throw new Zend_Exception('Unable to unset items via array interface');
	}

	public function offsetGet($offset)
	{
		return isset($this->_items[$offset]) ? $this->_items[$offset] : null;
	}
	
	public function setHighlight(Model_CompareTable_ItemSearch_Abstract $highlight)
	{
		$this->_highlight = $highlight;
		return $this;
	}
	
	public function getHighlightedItems()
	{
		if ($this->_highlight !== null) {
			return $this->_highlight->search($this);
		}
		return null;
	}
}