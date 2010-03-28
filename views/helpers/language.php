<?php
class LanguageHelper extends AppHelper {
	
/**
 * A simplified version of LayoutHelper::total. Outputs an "X times", but replaces '1 time' with 'once' and '2 times' with 'twice'. 
 * @param int $total The number of results
 */
	function times($total) {
		switch($total) {
			case 1:
				$out = 'once';
			break;
			case 2:
				$out = 'twice';
			break;
			default:
				$out = $total . ' times';
			break;
		}
		
		return $out;
	}
	
/**
 * Prefixes a specified field with 'the' where appropriate.
 *
 * @param  array  $url The location to link to.
 * @param  array   $options Extra options. 
 * @return string
 */
	function definite($text, $isDefinite) {
		if($isDefinite > 0) {
			$out = 'the ' . $text;
		} else {
			$out = $text;
		}
		return $out;
	}
	
/**
 * Returns a reflexive pronoun from a specified gender slug, e.g. 'himself' or 'herself'.
 *
 * @param  int     $gender The gender slug, e.g 'male'.
 * @param  array   $options Extra options. 
 * @return string
 */
	function reflexive($gender, $options = null) {
		switch($gender){
			case 'male':
				$reflexive = 'himself';
			break;
			case 'female':
				$reflexive = 'herself';
			break;
			default:
				$reflexive = 'themself';
			break;
		}
		
		if($options['allowSecondPerson'] == true && $user['username'] == $this->Session->read('User.username')) {
			$reflexive = 'yourself';
		}
		return $reflexive;
	}
	
/**
 * Generates a singular pronoun from the supplied gender slug. Optionally inflects and appends a specified verb. For example, "he thinks", "they think".
 *
 * @param  int     $gender The gender slug, e.g 'male'.
 * @param  array   $options Extra options. 
 * @return string
 */
	function singular($gender, $options = null) {
		switch($gender){
			case 'male':
				$reflexive = 'he';
				$isThirdPersonSingular = true;
			break;
			case 'female':
				$reflexive = 'she';
				$isThirdPersonSingular = true;
			break;
			default:
				$reflexive = 'they';
			break;
		}

		if(isset($options['username'])) {
			if($options['username'] == $this->Session->read('Auth.User.username')) {
				$reflexive = 'you';
			}
		}
		
		if(isset($options['verb'])) {
			$verb = ' ' . $options['verb'];
		}
		if($isThirdPersonSingular) {
			$end = 's'; 
		}
		
		return $reflexive . $verb . $end;
	}	
}
?>
