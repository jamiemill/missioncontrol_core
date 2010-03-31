<?php
class CoreBlocksController extends CoreAppController{
	
	function admin_delete($id = null, $type = null) {
		$this->CoreBlock->id = $id;
		$pageId = $this->CoreBlock->field('core_page_id');
		$this->redirectDestination = array('controller'=>'core_pages','action'=>'view',$pageId);
		parent::admin_delete($id, $type);
	}
	
	function admin_add() {
				
		$model = $this->modelNames[0];
		
		if(!empty($this->data)) { 
			if($this->{$model}->save($this->data)) {
				$this->_smartFlash(true, array('controller'=>'core_pages','action'=>'view',$this->data['CoreBlock']['core_page_id']));
			} else {
				$this->_smartFlash(false);
			}
		} else {
			if(isset($this->params['named']['content_area'])) {
				$this->data['CoreBlock']['content_area'] = $this->params['named']['content_area'];
			}
			$this->data['CoreBlock']['core_page_id'] = $this->params['named']['core_page_id'];
		}
		$this->CoreBlock->CorePage->id = $this->data['CoreBlock']['core_page_id'];
		$pageSlug = $this->CoreBlock->CorePage->field('slug');
		$this->set('contentAreas', $this->CoreBlock->CorePage->find('contentAreas',array('pageSlug'=>$pageSlug)));
		$this->set('page', $this->CoreBlock->CorePage->findById($this->data['CoreBlock']['core_page_id']));
		$this->_findLists($model);
	}
	
	function admin_edit($id = null) {
		
		$model = $this->modelNames[0];

		if(empty($this->data)) {	
			$this->{$model}->id = $id;
			$this->data = $this->{$model}->read();
		} else {
			if($this->{$model}->save($this->data)) {
				$this->_smartFlash(true, array('controller'=>'core_pages','action'=>'view',$this->data['CoreBlock']['core_page_id']));
			} else {
				$this->_smartFlash(false);
			}
		}
		$this->CoreBlock->CorePage->id = $this->data['CoreBlock']['core_page_id'];
		$pageSlug = $this->CoreBlock->CorePage->field('slug');
		$this->set('contentAreas', $this->CoreBlock->CorePage->find('contentAreas',array('pageSlug'=>$pageSlug)));
		$this->set('page', $this->CoreBlock->CorePage->findById($this->data['CoreBlock']['core_page_id']));
		$this->set('data', $this->CoreBlock->findById($id));
		$this->_findLists($model);
	}
	
	
	/**
	 * Generic 'admin_history' action.
	 */		 
	function admin_history($id = null) {
		$model = $this->modelNames[0];

		$this->{$model}->id = $id;
		$this->set('data', $this->{$model}->read());
		$this->set('revisions', $this->{$model}->revisions());
	}

	/**
	 * Generic 'admin_view_revision' action.
	 */		 
	function admin_view_revision($id = null, $revisionId = null) {
		$model = $this->modelNames[0];

		$this->{$model}->id = $id;
		$this->set('revision', $this->{$model}->revisions(array('conditions'=>array('version_id'=>$revisionId))));
	}
	/**
	 * Generic 'admin_restore' action.
	 */
	function admin_restore($id, $versionId) {
		$model = $this->modelNames[0];

		if($this->{$model}->revertTo($versionId)) {	
			$this->_smartFlash(true, $this->referer());
		} else {
			$this->_smartFlash(false);
		}
	}

}
?>
