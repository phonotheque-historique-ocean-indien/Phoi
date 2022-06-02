<?php
/**
* CLASS phonex
* phonex, phonetics search algo
* based on the algorithm described here : http://sqlpro.developpez.com/cours/soundex/ by Frédéric BROUARD
*
* author Johan Barbier <barbier_johan@hotmail.com>
* modification Gautier Michelin <gm@ideesculture.com> 2022
*/
class phonex {

	/**
	 * Ajout GM : remove accents
	 */

	private function remove_accents($string) {
		if ( !preg_match('/[\x80-\xff]/', $string) )
			return $string;
	
		$chars = array(
		// Decompositions for Latin-1 Supplement
		chr(195).chr(128) => 'A', chr(195).chr(129) => 'A',
		chr(195).chr(130) => 'A', chr(195).chr(131) => 'A',
		chr(195).chr(132) => 'A', chr(195).chr(133) => 'A',
		chr(195).chr(135) => 'C', chr(195).chr(136) => 'E',
		chr(195).chr(137) => 'E', chr(195).chr(138) => 'E',
		chr(195).chr(139) => 'E', chr(195).chr(140) => 'I',
		chr(195).chr(141) => 'I', chr(195).chr(142) => 'I',
		chr(195).chr(143) => 'I', chr(195).chr(145) => 'N',
		chr(195).chr(146) => 'O', chr(195).chr(147) => 'O',
		chr(195).chr(148) => 'O', chr(195).chr(149) => 'O',
		chr(195).chr(150) => 'O', chr(195).chr(153) => 'U',
		chr(195).chr(154) => 'U', chr(195).chr(155) => 'U',
		chr(195).chr(156) => 'U', chr(195).chr(157) => 'Y',
		chr(195).chr(159) => 's', chr(195).chr(160) => 'a',
		chr(195).chr(161) => 'a', chr(195).chr(162) => 'a',
		chr(195).chr(163) => 'a', chr(195).chr(164) => 'a',
		chr(195).chr(165) => 'a', chr(195).chr(167) => 'c',
		chr(195).chr(168) => 'e', chr(195).chr(169) => 'e',
		chr(195).chr(170) => 'e', chr(195).chr(171) => 'e',
		chr(195).chr(172) => 'i', chr(195).chr(173) => 'i',
		chr(195).chr(174) => 'i', chr(195).chr(175) => 'i',
		chr(195).chr(177) => 'n', chr(195).chr(178) => 'o',
		chr(195).chr(179) => 'o', chr(195).chr(180) => 'o',
		chr(195).chr(181) => 'o', chr(195).chr(182) => 'o',
		chr(195).chr(182) => 'o', chr(195).chr(185) => 'u',
		chr(195).chr(186) => 'u', chr(195).chr(187) => 'u',
		chr(195).chr(188) => 'u', chr(195).chr(189) => 'y',
		chr(195).chr(191) => 'y',
		// Decompositions for Latin Extended-A
		chr(196).chr(128) => 'A', chr(196).chr(129) => 'a',
		chr(196).chr(130) => 'A', chr(196).chr(131) => 'a',
		chr(196).chr(132) => 'A', chr(196).chr(133) => 'a',
		chr(196).chr(134) => 'C', chr(196).chr(135) => 'c',
		chr(196).chr(136) => 'C', chr(196).chr(137) => 'c',
		chr(196).chr(138) => 'C', chr(196).chr(139) => 'c',
		chr(196).chr(140) => 'C', chr(196).chr(141) => 'c',
		chr(196).chr(142) => 'D', chr(196).chr(143) => 'd',
		chr(196).chr(144) => 'D', chr(196).chr(145) => 'd',
		chr(196).chr(146) => 'E', chr(196).chr(147) => 'e',
		chr(196).chr(148) => 'E', chr(196).chr(149) => 'e',
		chr(196).chr(150) => 'E', chr(196).chr(151) => 'e',
		chr(196).chr(152) => 'E', chr(196).chr(153) => 'e',
		chr(196).chr(154) => 'E', chr(196).chr(155) => 'e',
		chr(196).chr(156) => 'G', chr(196).chr(157) => 'g',
		chr(196).chr(158) => 'G', chr(196).chr(159) => 'g',
		chr(196).chr(160) => 'G', chr(196).chr(161) => 'g',
		chr(196).chr(162) => 'G', chr(196).chr(163) => 'g',
		chr(196).chr(164) => 'H', chr(196).chr(165) => 'h',
		chr(196).chr(166) => 'H', chr(196).chr(167) => 'h',
		chr(196).chr(168) => 'I', chr(196).chr(169) => 'i',
		chr(196).chr(170) => 'I', chr(196).chr(171) => 'i',
		chr(196).chr(172) => 'I', chr(196).chr(173) => 'i',
		chr(196).chr(174) => 'I', chr(196).chr(175) => 'i',
		chr(196).chr(176) => 'I', chr(196).chr(177) => 'i',
		chr(196).chr(178) => 'IJ',chr(196).chr(179) => 'ij',
		chr(196).chr(180) => 'J', chr(196).chr(181) => 'j',
		chr(196).chr(182) => 'K', chr(196).chr(183) => 'k',
		chr(196).chr(184) => 'k', chr(196).chr(185) => 'L',
		chr(196).chr(186) => 'l', chr(196).chr(187) => 'L',
		chr(196).chr(188) => 'l', chr(196).chr(189) => 'L',
		chr(196).chr(190) => 'l', chr(196).chr(191) => 'L',
		chr(197).chr(128) => 'l', chr(197).chr(129) => 'L',
		chr(197).chr(130) => 'l', chr(197).chr(131) => 'N',
		chr(197).chr(132) => 'n', chr(197).chr(133) => 'N',
		chr(197).chr(134) => 'n', chr(197).chr(135) => 'N',
		chr(197).chr(136) => 'n', chr(197).chr(137) => 'N',
		chr(197).chr(138) => 'n', chr(197).chr(139) => 'N',
		chr(197).chr(140) => 'O', chr(197).chr(141) => 'o',
		chr(197).chr(142) => 'O', chr(197).chr(143) => 'o',
		chr(197).chr(144) => 'O', chr(197).chr(145) => 'o',
		chr(197).chr(146) => 'OE',chr(197).chr(147) => 'oe',
		chr(197).chr(148) => 'R',chr(197).chr(149) => 'r',
		chr(197).chr(150) => 'R',chr(197).chr(151) => 'r',
		chr(197).chr(152) => 'R',chr(197).chr(153) => 'r',
		chr(197).chr(154) => 'S',chr(197).chr(155) => 's',
		chr(197).chr(156) => 'S',chr(197).chr(157) => 's',
		chr(197).chr(158) => 'S',chr(197).chr(159) => 's',
		chr(197).chr(160) => 'S', chr(197).chr(161) => 's',
		chr(197).chr(162) => 'T', chr(197).chr(163) => 't',
		chr(197).chr(164) => 'T', chr(197).chr(165) => 't',
		chr(197).chr(166) => 'T', chr(197).chr(167) => 't',
		chr(197).chr(168) => 'U', chr(197).chr(169) => 'u',
		chr(197).chr(170) => 'U', chr(197).chr(171) => 'u',
		chr(197).chr(172) => 'U', chr(197).chr(173) => 'u',
		chr(197).chr(174) => 'U', chr(197).chr(175) => 'u',
		chr(197).chr(176) => 'U', chr(197).chr(177) => 'u',
		chr(197).chr(178) => 'U', chr(197).chr(179) => 'u',
		chr(197).chr(180) => 'W', chr(197).chr(181) => 'w',
		chr(197).chr(182) => 'Y', chr(197).chr(183) => 'y',
		chr(197).chr(184) => 'Y', chr(197).chr(185) => 'Z',
		chr(197).chr(186) => 'z', chr(197).chr(187) => 'Z',
		chr(197).chr(188) => 'z', chr(197).chr(189) => 'Z',
		chr(197).chr(190) => 'z', chr(197).chr(191) => 's'
		);
	
		$string = strtr($string, $chars);
	
		return $string;
	}


	/**
	* The public string we will work on
	*/
	public $sString = '';

	/**
	* private replacement array
	*/
	private $aReplaceGrp1 = array (
		'gan' => 'kan',
		'gam' => 'kam',
		'gain' => 'kain',
		'gaim' => 'kaim'
		);
	/**
	* private replacement array
	*/
	private $aReplaceGrp2 = array (
		'/(ain)([aeiou])/' => 'yn$2',
		'/(ein)([aeiou])/'=> 'yn$2',
		'/(aim)([aeiou])/' => 'yn$2',
		'/(eim)([aeiou])/'=> 'yn$2',
		);
	/**
	* private replacement array
	*/
	private $aReplaceGrp3 = array (
		'eau' => 'o',
		'oua' => '2',
		'ein' => '4',
		'ain' => '4',
		'eim' => '4',
		'aim' => '4'
		);
	/**
	* private replacement array
	*/
	private $aReplaceGrp4 = array (
		'é' => 'y',
		'ë' => 'y',
		'è' => 'y',
		'ai' => 'y',
		'ei' => 'y',
		'er' => 'yr',
		'ess' => 'yss',
		'et' => 'yt'
		);
	/**
	* private replacement array
	*/
	private $aReplaceGrp5 = array (
		'/(an)($|[^aeiou1234])/' => '1$2',
		'/(am)($|[^aeiou1234])/' => '1$2',
		'/(en)($|[^aeiou1234])/' => '1$2',
		'/(em)($|[^aeiou1234])/' => '1$2',
		'/(in)($|[^aeiou1234])/' => '4$2'
		);
	/**
	* private replacement array
	*/
	private $aReplaceGrp6 = array (
		'on' => '1'
		);
	/**
	* private replacement array
	*/
	private $aReplaceGrp7 = array (
		'/([aeiou1234])(s)([aeiou1234])/' => '$1z$3'
		);
	/**
	* private replacement array
	*/
	private $aReplaceGrp8 = array (
		'oe' => 'e',
		'eu' => 'e',
		'au' => 'o',
		'oi' => '2',
		'oy' => '2',
		'ou' => '3'
		);
	/**
	* private replacement array
	*/
	private $aReplaceGrp9 = array (
		'ch' => '5',
		'sch' => '5',
		'sh' => '5',
		'ss' => 's',
		'sc' => 's'
		);
	/**
	* private replacement array
	*/
	private $aReplaceGrp10 = array (
		'/(c)([ei])/' => 's$2'
		);
	/**
	* private replacement array
	*/
	private $aReplaceGrp11 = array (
		'c' => 'k',
		'q' => 'k',
		'qu' => 'k',
		'gu' => 'k',
		'ga' => 'ka',
		'go' => 'ko',
		'gy' => 'ky'
		);
	/**
	* private replacement array
	*/
	private $aReplaceGrp12 = array (
		'a' => 'o',
		'd' => 't',
		'p' => 't',
		'j' => 'g',
		'b' => 'f',
		'v' => 'f',
		'm' => 'n'
		);
	/**
	* private replacement array
	*/
	private static $aReplaceGrp13 = array (
			'1',
		 	'2',
		 	'3',
		 	'4',
		 	'5',
		 	'e',
		 	'f',
		 	'g',
		 	'h',
		 	'i',
		 	'k',
		 	'l',
		 	'n',
		 	'o',
		 	'r',
		 	's',
		 	't',
		 	'u',
		 	'w',
		 	'x',
		 	'y',
		 	'z'
		);
	/**
	* private replacement array
	*/
	private $aEnd = array (
		't',
		'x'
		);

	/**
	* public function build ()
	* main method, building the phonex code of a given string
	* @Param string sString : the string!
	*/
	public function build ($sString) {
		if (is_string ($sString) && !empty ($sString)) {
			$this -> sString = $sString;
		} else {
			trigger_error ('Parameter string must not be empty', E_USER_ERROR);
		}
		$this -> sString = strtolower ($this -> sString)." ";
		// Cas adelaid
		$this -> sString = preg_replace( '/adéla/', 'adela', $this -> sString );
		$this -> sString = preg_replace( '/adelaïd/', 'adelaide', $this -> sString );
		$this -> sString = preg_replace( '/adelaid/', 'adelaide', $this -> sString );
		$this -> sString = preg_replace( '/adelaid[e]+/', 'adelaide', $this -> sString );
		$this -> sString = preg_replace( '/adelaide/', 'adelaïde', $this -> sString );
		//$this -> sString = $this->remove_accents($this -> sString);
		//var_dump($this -> sString);die();
		$this -> sString = preg_replace( '/ée/', 'é', $this -> sString );
		$this -> sString = preg_replace( '/ette /', 'et ', $this -> sString );
		$this -> sString = preg_replace( '/ete /', 'et ', $this -> sString );
		$this -> sString = preg_replace( '/elle /', 'el ', $this -> sString );
		$this -> sString = preg_replace( '/ele /', 'el ', $this -> sString );
		// Cas bonne/bone
		$this -> sString = preg_replace( '/nne/', 'ne', $this -> sString );
		// Cas chihabee/chihabi
		$this -> sString = preg_replace( '/ee/', 'i', $this -> sString );
		// Cas aboo/abou
		$this -> sString = preg_replace( '/oo/', 'ou', $this -> sString );
		// Cas waro
		$this -> sString = preg_replace( '/oua/', 'wa', $this -> sString );
		$this -> sString = preg_replace( '/oa/', 'wa', $this -> sString );

		// Cas chantons, chansons, ...
		$this -> sString = preg_replace( '/ons/', 'on', $this -> sString );

		// Cas zot
		$this -> sString = preg_replace( '/zot/', 'zote', $this -> sString );
		$this -> sString = preg_replace( '/zot[e]+/', 'zote', $this -> sString );
		
		// Son "y" dans ill		
		$this -> sString = preg_replace( '/famille/', 'famiye', $this -> sString );
		$this -> sString = preg_replace( '/mille/', 'mil', $this -> sString );
		$this -> sString = preg_replace( '/ville/', 'vil', $this -> sString );
		$this -> sString = preg_replace( '/ill/', 'y', $this -> sString );

		$this -> sString = preg_replace( '/ca/', 'ka', $this -> sString );
		$this -> sString = preg_replace( '/co/', 'ko', $this -> sString );
		$this -> sString = preg_replace( '/cu/', 'ku', $this -> sString );

		//
		$this -> sString = str_replace (' ', '', $this -> sString);
		$this -> sString = str_replace ('y', 'i', $this -> sString);
		$this -> sString = preg_replace ('/(?<![csp])h/', '', $this -> sString);
		$this -> sString = str_replace ('ph', 'f', $this -> sString);
		$this -> aReplace ($this -> aReplaceGrp1);
		$this -> aReplace ($this -> aReplaceGrp2, true);
		$this -> aReplace ($this -> aReplaceGrp3);
		$this -> aReplace ($this -> aReplaceGrp4);
		$this -> aReplace ($this -> aReplaceGrp5, true);
		$this -> aReplace ($this -> aReplaceGrp6);
		$this -> aReplace ($this -> aReplaceGrp7, true);
		$this -> aReplace ($this -> aReplaceGrp8);
		$this -> aReplace ($this -> aReplaceGrp9);
		$this -> aReplace ($this -> aReplaceGrp10, true);
		$this -> aReplace ($this -> aReplaceGrp11);
		$this -> aReplace ($this -> aReplaceGrp12);
		$this -> sString = preg_replace( '/(.)\1/', '$1', $this -> sString );
		$this -> trimLast ();
		$this -> getNum ();
		return $this->sString;
	}

	/**
	* private function aReplace ()
	* method used to replace letters, given an array
	* @Param array aTab : the replacement array to be used
	* @Param bool bPreg : is the array an array of regular expressions patterns : true => yes`| false => no
	*/
	private function aReplace (array $aTab, $bPreg = false) {
		if (false === $bPreg) {
			$this -> sString = str_replace (array_keys ($aTab), array_values ($aTab), $this -> sString);
		} else {
			$this -> sString = preg_replace (array_keys ($aTab), array_values ($aTab), $this -> sString);
		}
	}

	/**
	* private function trimLast ()
	* method to trim the bad endings
	*/
	private function trimLast () {
		$length = strlen ($this -> sString) - 1;
		if (in_array ($this -> sString{$length}, $this -> aEnd)) {
			$this -> sString = substr ($this -> sString, 0, $length);
		}
	}

	private function trimFirst() {
		// remove first letter if H
		if(substr($this -> sString, 0 , 1) == "h") {
			$this -> sString = substr ($this -> sString, 1, strlen($this -> sString)-1);
		}
	}

	/**
	* private static function mapNum ()
	* callback method to create the phonex numeric code, base 22
	* @Param int val : current value
	* @Param int clef : current key
	* @Returns int num : the calculated base 22 value
	*/
	 private static function mapNum ($val, $clef) {
		$num = array_search ($val, self::$aReplaceGrp13);
		$num *= pow (22, - ($clef + 1));
		return $num;
	}

	/**
	* private function getNum ()
	* method to get a numeric array from the main string
	* we call the callback function mapNum and we sum all the values of the obtained array to get the final phonex code
	*/
	private function getNum () {
		$aString = str_split ($this -> sString);
		$aNum = array_map (array ('self', 'mapNum'), array_values ($aString), array_keys ($aString));
		$this -> sString = (string) array_sum ($aNum);
	}
}
?>