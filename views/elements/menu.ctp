<?php 


$menu = $this->requestAction('/core/core_pages/sitemap/');



// pagePath is passed in as a parameter when this element is requested
// eg. in controller $pagePath = $this->CorePage->getpath($pageData['CorePage']['id'])

if(!isset($pagePath)) {
	$pagePath = array();
}
if(!isset($depthLimit)) {
	$depthLimit = 999;
}
if(!isset($initialDepth)) {
	$initialDepth = 0;
}
if(!isset($baseListId)) {
	$baseListId = 'mainmenu';
}
if(!isset($baseListClass)) {
	$baseListClass = 'nav';
}
if(!isset($li_class_prefix)) {
	$li_class_prefix = 'menu_';
}
if(!isset($parentsAreLinks)) {
	$parentsAreLinks = true;
}


// removed to controller, so that it's easier to construct manually for pages outside the 'core_pages' model
//$pagePath = Set::extract($pagePath,'{n}.Page.id');

// nestedMenu is a custom helper
if(isset($nestedMenu)) echo $nestedMenu->generate($menu, array(
	'initialDepth' => $initialDepth,
	'currentPath'=>$pagePath,
	'depthLimit' => $depthLimit,
	'model'=>'CorePage',
	'display'=>'title',
	'baseListId'=>$baseListId,
	'baseListClass'=>$baseListClass,
	'li_class_prefix'=>$li_class_prefix,
	'parentsAreLinks'=>$parentsAreLinks
	));

?>