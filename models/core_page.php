<?php
class CorePage extends CoreAppModel {

	var $actsAs = array(
		'Core.Sluggable' => array('label' => 'title'),
		'Tree'
	);
	
	var $hasMany = array(
		'CoreBlock'=>array(
			'className'=>'Core.CoreBlock',
			'order'=>array(
				'CoreBlock.content_area'=>'asc'
			),
			'conditions'=>array('CoreBlock.deleted'=>false)
		),
		'CoreCustomField'=>array(
			'className'=>'Core.CoreCustomField'
		)
	);
	
	function loadValidation() {
		$this->validate = array(
			'slug'=>array(
				'slug'=>array(
					'rule'=>'/^[a-z0-9\-]+$/',
					'message'=>__('Must only contain letters, numbers and hyphens.',true)
				),
				'notempty'=>array(
					'rule'=>'notEmpty',
					'message'=>sprintf(__('Please enter a %s.',true), 'slug')
				),
				'unique'=>array(
					'rule'=>'isUnique',
					'message'=>__('Must be unique.',true)
				),
			),
			'title'=>array(
				'notempty'=>array(
					'rule'=>'notEmpty',
					'message'=>sprintf(__('Please enter a %s.',true),__('title',true))
				),
			)
		);
	}
	
	function find($type, $options = array()) {
		switch($type) {
			case 'views':
				return $this->__findViewFiles($options);
			case 'layouts':
				return $this->__findLayoutFiles($options);
			case 'contentAreas':
				return $this->__findContentAreas($options);
			default:
				return parent::find($type, $options);
		}
	}
	
	function __findViewFiles() {

		$folder = new Folder;
		$viewFiles = array();
	
		$folder->path = Configure::read('MissionControl.pageViewsFolder');
		$files = $folder->read();
		foreach($files[1] as $file) {
			$file = str_replace('.ctp', '', $file);
			if(preg_match('/^(admin_|\.)/',$file)) continue;
			$viewFiles[$file] = $file;
		}
		
		return $viewFiles;	
	}
	
	function __findLayoutFiles() {

		$folder = new Folder;
	 	$layoutFiles = array();
		
		foreach(App::path('views') as $viewPath) {
			$folder->path = $viewPath . 'layouts';
			$files = $folder->read();
		
			foreach($files[1] as $file) {
				$file = str_replace('.ctp', '', $file);
				$layoutFiles[$file] = $file;
			}
		}
		return $layoutFiles;	
	}
	
	function __findContentAreas($options) {
		$pageSlug = $options['pageSlug'];
		$this->recursive = -1;
		$page = $this->findBySlug($pageSlug);
		if(!in_array($page['CorePage']['view_file'],$this->__findViewFiles())) {
			return false;
		}
		if(!in_array($page['CorePage']['layout_file'],$this->__findLayoutFiles())) {
			return false;
		}
		$layout = $this->requestAction('/admin/core/core_pages/layout/'.$pageSlug);
		preg_match_all('/\{\{(?P<contentAreas>[a-z1-9\-_]+)\}\}/', $layout, $matches);
		if(empty($matches['contentAreas'])) {
			return array();
		}
		return array_combine($matches['contentAreas'], $matches['contentAreas']);
	}
}
?>
