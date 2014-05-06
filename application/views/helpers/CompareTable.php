<?php 

class Zend_View_Helper_CompareTable extends Zend_View_Helper_Abstract
{
	const TITLE_RECURRENCE = 5;
	
	public function compareTable(Model_CompareTable $table)
	{
		$tableWidth = count($table->getColumns()) + 1;
		$rowCount = 0;
		$str = '<table class="blockcenter">';
		foreach ($table as $row) {
			$rowCount %= self::TITLE_RECURRENCE;
			if ($rowCount == 0) {
				$str .= $this->view->compareTitles($table->getColumns());
			}
			$str .= $this->_row($row);
			$rowCount++;
		}
		$str .= '</table>';
		return $str;
	}
	
	protected function _row(Model_CompareTable_Row $row)
	{
		$str = '<tr><td class="comparerowtitle">'.$row->getTitle().'</td>';
		$highlight = $row->getHighlightedItems();
		if ($highlight === null) {
			$highlight = array();
		}
		foreach ($row as $item) {
			$str .= $this->_item($item, in_array($item, $highlight));
		}
		$str .= '</tr>';
		return $str;
	}
	
	protected function _item(Model_CompareTable_Item $item, $highlight = false)
	{
		$str = '<td class="comparetabledata';
		if ($highlight) $str .= ' comparehighlight';
		$str .= '">';
		$str .= $item->getValue();
		$str .= '</td>';
		return $str;
	}
}