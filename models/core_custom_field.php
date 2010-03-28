<?php
class CoreCustomField extends CoreAppModel {

	var $belongsTo = array(
		'CorePage'=>array(
			'className'=>'Core.CorePage'
		)
	);
	
	var $validate = array(
		'name'=>array(
			'notempty'=>array(
				'rule'=>'notEmpty',
				'message'=>'Name required.'
			),
		),
		'value'=>array(
			'notempty'=>array(
				'rule'=>'notEmpty',
				'message'=>'Value required.'
			),
		),
	);
	
}
?>
