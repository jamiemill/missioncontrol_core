<?php $menu = $this->requestAction('/pages/page_descendents/'.$parent_id);?> 

<?php 

//pr($menu);
//pr($pagePath);

//$pagePath = Set::extract($pagePath,'{n}.Page.id');


echo $nestedMenu->generate($menu,array('initialDepth' => 0,'currentPath'=>$pagePath, 'depthLimit' => 100,'model'=>'CorePage','display'=>'title','baseListClass'=>'page_children'));

 ?>