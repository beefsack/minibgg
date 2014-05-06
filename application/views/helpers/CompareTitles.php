<?php 

class Zend_View_Helper_CompareTitles extends Zend_View_Helper_Abstract
{
	public function compareTitles(array $titles) {
		return '<tr><td class="comparetitles"></td><td class="comparetitles">'.implode('</td><td class="comparetitles">', $titles).'</td></tr>';
	}
}