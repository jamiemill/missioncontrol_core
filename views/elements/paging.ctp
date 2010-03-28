<?php

$params = $this->passedArgs;
unset($params['page']);

?>
<div class="paging">
	<?php echo $paginator->prev('&lsaquo; '.__('previous', true), array('escape'=>false,'url'=>$params), null, array('class'=>'disabled','escape'=>false,'url'=>$params));?>
 | 	<?php echo $paginator->numbers(array('url'=>$params));?> |
	<?php echo $paginator->next(__('next', true).' &rsaquo;', array('escape'=>false,'url'=>$params), null, array('class'=>'disabled','escape'=>false,'url'=>$params));?>
</div>