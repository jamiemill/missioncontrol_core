<?php
class CoreBlock extends CoreAppModel {

	var $actsAs = array('Core.Revision','Core.SoftDeletable');

	var $belongsTo = array('Core.CorePage', 'Core.CoreBlockType');

}
?>