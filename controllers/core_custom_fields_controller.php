<?php
class CoreCustomFieldsController extends CoreAppController{
	
	function admin_delete($id = null, $type = null) {
		$this->CoreCustomField->id = $id;
		$pageId = $this->CoreCustomField->field('core_page_id');
		$this->redirectDestination = array('controller'=>'core_pages','action'=>'view',$pageId);
		parent::admin_delete($id, $type);
	}
	
	function admin_add() {
				
		$model = $this->modelNames[0];
		
		if(!empty($this->data)) { 
			if($this->{$model}->save($this->data)) {
				$this->_smartFlash(true, array('controller'=>'core_pages','action'=>'view',$this->data['CoreCustomField']['core_page_id']));
			} else {
				$this->_smartFlash(false);
			}
	    } else {
			if(isset($this->params['named']['core_page_id'])) {
				$this->data['CoreCustomField']['core_page_id'] = $this->params['named']['core_page_id'];
			}
		}
	}
	
	function admin_edit($id=null) {
				
		$model = $this->modelNames[0];
		
		if(!empty($this->data)) { 
			if($this->{$model}->save($this->data)) {
				$this->_smartFlash(true, array('controller'=>'core_pages','action'=>'view',$this->data['CoreCustomField']['core_page_id']));
			} else {
				$this->_smartFlash(false);
			}
	    } else {
			$this->CoreCustomField->id = $id;
			$this->data = $this->CoreCustomField->read();
		}
	}
	
	


}
?>
