<div class="record_navigation">
	<?php echo $nav->back(array('controller' => 'core_pages', 'action' => 'view', $data['CorePage']['id']),array('text'=>'back to page')); ?>
</div>
<?php 
$html->addCrumb('Pages',array('controller'=>'core_pages','action'=>'index'));
$html->addCrumb($data['CorePage']['title'],array('controller'=>'core_pages','action'=>'view',$data['CorePage']['id']));
$html->addCrumb($data['CoreBlock']['content_area'].' block');
$html->addCrumb('history');
?>
<?php echo $this->element('crumb_heading', array('plugin'=>'core'))?>


<div class="main_no_sidebar">
	<?php if(!empty($revisions)) : ?>
	<ul class="admin_listing">
		<li class="block">
			<div class="actions">
				<strong><?php __('Current version.') ?></strong><br />
				<em><?php echo $time->niceShort($data['CoreBlock']['modified']) ?></em>
			</div> 
			<?php echo $text->truncate(strip_tags($data['CoreBlock']['content']),500); ?>
			<div class="clear"></div>
		</li>
		<?php foreach($revisions as $revision) : ?> 
		<li class="block">
			<div class="actions">
				<em><?php echo $time->niceShort($revision['CoreBlock']['version_created']) ?></em>
				<?php echo $nav->restore(array($revision['CoreBlock']['id'], $revision['CoreBlock']['version_id'])); ?><br />
				<?php echo $html->link(__('View Full',true),array('controller'=>'core_blocks','action'=>'view_revision',$revision['CoreBlock']['id'],$revision['CoreBlock']['version_id'])); ?><br />
			</div> 
			<?php echo $text->truncate(strip_tags($revision['CoreBlock']['content']),500); ?>
			<div class="clear"></div>
		</li>
		<?php endforeach ?> 
	</ul>
	<?php endif ?> 
</div>
