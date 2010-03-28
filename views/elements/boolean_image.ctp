<?php

if(isset($value)) {
	
	if($value) {
		echo $html->image('/core/img/admin/tick.png');
	}
	else {
		echo $html->image('/core/img/admin/cross.png');
	}
	
}



?>