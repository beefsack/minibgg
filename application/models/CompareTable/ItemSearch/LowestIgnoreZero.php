<?php

class Model_CompareTable_ItemSearch_LowestIgnoreZero extends Model_CompareTable_ItemSearch_Abstract
{
	public function search(Model_CompareTable_Row $row) {
		$lowest = null;
		$array = array();
		foreach ($row as $item) {
			if ($item->getValue() != 0) {
				if ($lowest === null || $item->getValue() < $lowest) {
					$lowest = $item->getValue();
					$array = array($item);
				} elseif ($lowest !== null && $item->getValue() == $lowest) {
					$array[] = $item;
				}
			}
		}
		return $array;
	}
}