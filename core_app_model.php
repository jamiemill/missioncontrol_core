<?php
class CoreAppModel extends AppModel {
	
	var $actsAs = array('Containable');
	
	/**
	* Placeholder to be overridden in child classes.
	* Instead of declaring validation in $validate, do it within this function if you wish to have translated
	* validation messages.
	*/
	function loadValidation() {

	}
	
	function beforeValidate() {
		if(!parent::beforeValidate()) {
			return false;
		}
		$this->loadValidation();
	}

}
?>
