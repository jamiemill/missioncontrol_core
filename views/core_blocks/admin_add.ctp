
<?php 
$html->addCrumb('Pages',array('controller'=>'core_pages','action'=>'index'));
$html->addCrumb($page['CorePage']['title'],array('controller'=>'core_pages','action'=>'view',$page['CorePage']['id']));
$html->addCrumb('add block');
?>
<?php echo $this->element('crumb_heading', array('plugin'=>'core'))?>
<div class="main_no_sidebar">
	<div class="box">
		<div class="box_head">
			<h2><?php echo sprintf(__('Add %s',true), __('block',true)) ?></h2>
		</div>
		<div class="box_content">
			<?php echo $form->create('CoreBlock') ?> 
				<?php echo $this->element('block_inputs'); ?> 
			<?php echo $form->end('Save') ?> 
		</div>
	</div>
</div>