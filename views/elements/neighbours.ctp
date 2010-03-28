<?php

/*

creates next and previous buttons
use like:

echo $this->element('neighbours',array('neighbours'=>$neighbours))

$neighbours is a view var set by pages_controller automatically

*/

if(isset($neighbours)) {


	if(!isset($divider)) {
		$divider = ' | ';
	}
	if(!isset($showUnavailable)) {
		$showUnavailable = true;
	}
	if(!isset($nextText)) {
		$nextText = 'Next';
	}
	if(!isset($previousText)) {
		$previousText = 'Previous';
	}

	//pr($neighbours);

	if(!empty($neighbours['prev'])) {
		echo $html->link($previousText,'/pages/'.$neighbours['prev']['CorePage']['slug'],array('class'=>'prev'));
	}
	elseif($showUnavailable) {
		echo $previousText;
	}

	echo $divider;

	if(!empty($neighbours['next'])) {
		echo $html->link($nextText,'/pages/'.$neighbours['next']['CorePage']['slug'],array('class'=>'next'));
	}
	elseif($showUnavailable) {
		echo $nextText;
	}

}

?>

