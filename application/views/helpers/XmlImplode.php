<?php 

class Zend_View_Helper_XmlImplode extends Zend_View_Helper_Abstract
{
	public function xmlImplode($glue, SimpleXMLElement $pieces, $substitutionString = null) {
		$array = array();
		foreach ($pieces as $piece) {
			if ($substitutionString === null) {
				$str = (string) $piece;
			} else {
				$str = $substitutionString;
				if (preg_match('/\{(.*?)\}/', $substitutionString, $matches)) {
					//Zend_Debug::dump($matches);
					for ($i = 1; $i < count($matches); $i++) {
						if (isset($piece[$matches[$i]])) {
							$str = preg_replace('/\{'.preg_quote($matches[$i]).'\}/', $piece[$matches[$i]], $str);
						}
					}
					$str = preg_replace('/\{elementContent\}/', (string) $piece, $str);
				}
			}
			$array[] = $str;
		}
		return (implode($glue, $array));
	}
}