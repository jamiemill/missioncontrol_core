<?php
class CorePagesController extends CoreAppController{

	function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow(array('view','sitemap', 'request_page_data', 'children'));
	}
	
	function admin_index() {
		$model = $this->modelNames[0];
		
		$this->set('data',$this->CorePage->find('threaded',array('order'=>array('CorePage.lft'))));
    }
	
	function view($slug = null,$forLayout = false) {
		$model = $this->modelNames[0];
		
		if($slug && !$forLayout) {
			$item = $this->{$model}->find('first',array('conditions'=>array(
				'CorePage.slug'=>$slug,
				'CorePage.is_published'=>true,
				'CorePage.publish_time <'=>date('Y-m-d G:i:s')
			)));
		} elseif($slug && $forLayout) {
			$item = $this->{$model}->find('first',array('conditions'=>array(
				'CorePage.slug'=>$slug
			)));
		}
		if(empty($item)) {
			if($forLayout) {
				return false;
			}
			$this->cakeError('error404');
			return;
		}
		
		// if we don't allow parent content, redirect...
		if(($item['CorePage']['rght']-$item['CorePage']['lft']) > 1 && 
			(Configure::read('Site.allowPageParentContent') === false || $item['CorePage']['redirect_to_first_child'])
		) {
			if($forLayout) {
				return false;
			}
			$child = $this->CorePage->find('first',array('conditions'=>'CorePage.lft > '.$item['CorePage']['lft'],'fields'=>'CorePage.slug','order'=>'CorePage.lft'));
			$this->redirect(array('plugin'=>'core','controller'=>'core_pages','action'=>'view', $child['CorePage']['slug']),301,true); // 301 is search engine friendly, default 302 is not.
			return false;
		}
		
		$this->set('data', $item);
		
		$this->pageTitle = $item['CorePage']['title'];
		
		$pagePath = $this->CorePage->getpath($item['CorePage']['id'],array('id'));
		$pagePath = Set::extract('/CorePage/id',$pagePath);
		$this->set('pagePath', $pagePath);
		
		if(!empty($this->viewVars['data']['CorePage']['layout_file'])) {
			$this->layout = $this->viewVars['data']['CorePage']['layout_file'];
		}
		if(empty($this->viewVars['data']['CorePage']['view_file'])) {
			trigger_error('No view file selected.');
		}

		return $this->render(
			false,
			null,
			Configure::read('MissionControl.pageViewsFolder') . $this->viewVars['data']['CorePage']['view_file'].'.ctp'
		);

	}
	
	function request_page_data($slug) {
		return $this->CorePage->findBySlug($slug);
	}
	
	function admin_view($id = null) {
		parent::admin_view($id);
		$this->set('contentAreas',$this->CorePage->find('contentAreas',array('pageSlug'=>$this->viewVars['data']['CorePage']['slug'])));
	}
	
	/**
	* Special method callable by a request action to return the view with all its placeholders outputted like: {{slug}}
	* which is used to find the available placeholders for any given view.
	*/
	
	function admin_layout($slug = null) {
		$this->view($slug,true);
	}
	
	function admin_rewind($id = null) {
		if(!empty($this->data['CorePage']['revision_date'])) {
			$this->passedArgs['date'] = implode('-', array_reverse($this->data['CorePage']['revision_date']));
			$this->redirect($this->passedArgs);
		}
		if($id && $this->passedArgs['date']) {
			$this->CorePage->contain(array(
				'CoreBlock' => array(
					'BlockType', 
					'Revision'
				)
			));
			$page = $this->CorePage->find('first', array(
				'conditions' => array('Page.id' => $id),
				// Use an inner join between Block and Revision, as we don't want to retrieve blocks that have no revisions at the specified date. 
				'joins' => array(
					array(
						'table' => 'revisions',
						'alias' => 'Revision',
						'type' => 'inner',
						'conditions' => array('Revision.created <' => $this->passedArgs['date'], 'Revision.is_visible >' => 0)
					)
				)
			));
		}
		if(empty($page)) {
			$this->_smartFlash(__('Sorry. That page could not be viewed.',true));
		}
		$this->set('page', $page);
	}
	
	function admin_add() {
		$this->set('viewFiles', $this->CorePage->find('views'));
		$this->set('layoutFiles', $this->CorePage->find('layouts'));
		
		parent::admin_add();
		
		$this->set('parents',$this->CorePage->generatetreelist(null,'{n}.CorePage.id','{n}.CorePage.title','-- '));
		
		if(empty($this->data['CorePage']['view_file'])) {
			$this->data['CorePage']['view_file'] = 'default';
		}
		if(empty($this->data['CorePage']['layout_file'])) {
			$this->data['CorePage']['layout_file'] = 'default';
		}
	}
	
	function admin_edit($id = null) {
		$this->set('viewFiles', $this->CorePage->find('views'));
		$this->set('layoutFiles', $this->CorePage->find('layouts'));
		
		parent::admin_edit($id);
		
		$this->set('parents',$this->CorePage->generatetreelist('CorePage.id !='.$id,'{n}.CorePage.id','{n}.CorePage.title','-- '));
	}
	
	function sitemap() {
		$this->CorePage->contain();
		
		$result = $this->CorePage->find('threaded',array(
			'conditions'=>array(
				'CorePage.show_in_menu'=>true, 
				'CorePage.is_published'=>true,
				'CorePage.publish_time <'=>date('Y-m-d G:i:s')
			),
			'order'=>array(
				'CorePage.lft'
			)
		));
		
		if(isset($this->params['requested'])) { 
			return $result;
		}
		$this->set('data',$result);
	}
	
	function admin_sitemap() {
		$this->CorePage->contain();
		
		$result = $this->CorePage->find('threaded',array(
			'conditions'=>array(
				'CorePage.is_published'=>true,
				'CorePage.publish_time <'=>date('Y-m-d G:i:s')
			),
			'order'=>array(
				'CorePage.lft'
			)
		));
		
		if(isset($this->params['requested'])) { 
			return $result;
		}
		$this->set('data',$result);
	}
	
	function admin_recover() {
		debug($this->CorePage->recover());
		exit(__('Complete',true));
	}
	
	
	function admin_move_up($id) {
		$model = $this->modelNames[0];
		
		if(isset($id) && is_numeric($id)) {
			if($this->{$model}->moveup($id, 1)) {
				$this->_smartFlash(true, $this->referer());
			} else {
				$this->_smartFlash(__('Sorry. That item could not be moved up any further.',true), $this->referer());
			}
		}
	}

	function admin_move_down($id) {
		$model = $this->modelNames[0];
		
		if(isset($id) && is_numeric($id)) {
			if($this->{$model}->movedown($id, 1)) {
				$this->_smartFlash(true, $this->referer());
			} else {
				$this->_smartFlash(__('Sorry. That item could not be moved down any further.',true), $this->referer());
			}
		}
	}
	
	function children($id) {
		$entry = $this->CorePage->read(array('lft','rght'), $id);
		$cond = array(
			'CorePage.lft >=' => $entry['CorePage']['lft'],
			'CorePage.rght <=' => $entry['CorePage']['rght'],
			'CorePage.show_in_menu'=>true, 
			'CorePage.is_published'=>true,
			'CorePage.publish_time <'=>date('Y-m-d G:i:s')
		);
		
		$result = $this->CorePage->find('threaded', array('conditions'=>$cond,'order'=>'CorePage.lft')); 
		if(!empty($result)) {
			return $result[0]['children'];
		}
		else {
			return array();
		}
	}
	
}
?>
