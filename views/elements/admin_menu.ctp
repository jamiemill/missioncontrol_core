<?php

$base = $html->url('/');

echo $menu->menu(array( 
	    __('Dashboard',true) => array('route'=>array('admin'=>true,'plugin'=>'core','controller' => 'core_dashboard', 'action'=>'index'),'match'=>"^{$base}(admin/core/core_dashboard|admin$)"),
		__('Pages',true) => array('route'=>array('admin'=>true,'plugin'=>'core','controller' => 'core_pages', 'action'=>'index'),'match'=>"^{$base}admin/core/(core_pages|core_blocks)"),
		__('File Library',true) => array('route'=>array('admin'=>true,'plugin'=>'file_library','controller' => 'file_library_files', 'action'=>'index'),'match'=>"^{$base}admin/file_library"),
		__('News',true) => array('route'=>array('admin'=>true,'plugin'=>'news','controller' => 'news_articles', 'action'=>'index'),'match'=>"^{$base}admin/news"),
		__('Users',true) => array('route'=>'/admin/users','match'=>"^{$base}admin/users"), // plugin array routing not working for this.. don't know why
	), 
	array('class' => 'main_menu')
);

?>