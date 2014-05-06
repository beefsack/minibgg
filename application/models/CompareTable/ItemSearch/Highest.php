<?php

class Model_CompareTable_ItemSearch_Highest extends Model_CompareTable_ItemSearch_Abstract
{
	public function search(Model_CompareTable_Row $row) {
		$highest = null;
		$array = array();
		foreach ($row as $item) {
			if ($highest === null || $item->getValue() > $highest) {
				$highest = $item->getValue();
				$array = array($item);
			} elseif ($highest !== null && $item->getValue() == $highest) {
				$array[] = $item;
			}
		}
		return $array;
	}
}