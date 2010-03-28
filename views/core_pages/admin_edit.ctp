<?php 
$html->addCrumb(__('Pages',true),array('controller'=>'core_pages','action'=>'index'));
$html->addCrumb($data['CorePage']['title'],array('controller'=>'core_pages','action'=>'view',$data['CorePage']['id']));
$html->addCrumb(__('Settings',true));
?>
<?php echo $this->element('crumb_heading', array('plugin'=>'core'))?>

<div class="main">
	<div class="box">
		<div class="box_head">
			<h2><?php __('Settings') ?></h2>
		</div>
		<div class="box_content">
			<?php echo $form->create('CorePage') ?>
				<?php echo $form->input('id'); ?> 
				<?php echo $this->element('page_inputs'); ?> 
			<?php echo $form->end() ?> 
		</div>
	</div>
</div>
