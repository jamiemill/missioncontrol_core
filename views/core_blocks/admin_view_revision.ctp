<div class="record_navigation">

</div>
<?php 
$html->addCrumb(__('Pages',true),array('controller'=>'core_pages','action'=>'index'));
$html->addCrumb(__('view',true).' '.__('block revision',true));
?>
<?php echo $this->element('crumb_heading', array('plugin'=>'core'))?>


<div class="main_no_sidebar">
	<?php if(!empty($revision)) : ?>
	<ul class="admin_listing">
		<li class="block">
			<div class="actions">
				<em><?php echo $time->niceShort($revision[0]['CoreBlock']['version_created']) ?></em>
				<?php echo $nav->restore(array($revision[0]['CoreBlock']['id'], $revision[0]['CoreBlock']['version_id'])); ?><br />
			</div> 
			<?php echo $revision[0]['CoreBlock']['content']; ?>
			<div class="clear"></div>
		</li>
	</ul>
	<?php endif ?> 
</div>
