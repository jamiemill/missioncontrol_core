<div class="record_navigation">
	<?php echo $nav->back(array('controller' => 'core_pages', 'action' => 'view', $page['CorePage']['id']),array('text'=>'back to page')); ?>
</div>
<?php 
$html->addCrumb('Pages',array('controller'=>'core_pages','action'=>'index'));
$html->addCrumb($page['CorePage']['title'],array('controller'=>'core_pages','action'=>'view',$page['CorePage']['id']));
$html->addCrumb($data['CoreBlock']['content_area'].' block');
$html->addCrumb('edit');
?>
<?php echo $this->element('crumb_heading', array('plugin'=>'core'))?>
<div class="main_no_sidebar">
	<div class="box">
		<div class="box_head">
			<h2><?php echo sprintf(__('Edit %s',true), __('block',true)) ?></h2>
		</div>
		<div class="box_content">
			<?php echo $form->create('CoreBlock') ?> 
				<?php echo $form->input('id') ?>
				<?php echo $this->element('block_inputs') ?> 
			<?php echo $form->end(__('Save',true)) ?> 
		</div>
	</div>
</div>