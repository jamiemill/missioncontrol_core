<div class="main">
	<div class="box">
		<div class="box_head">
			<h2><?php __('Add Custom Field') ?></h2>
		</div>
		<div class="box_content">
			<?php echo $form->create('CoreCustomField') ?>
				<?php echo $form->input('core_page_id', array('type'=>'hidden')) ?>
				<?php echo $form->input('name') ?>
				<?php echo $form->input('value') ?>
			<?php echo $form->end('Save') ?> 
		</div>
	</div>
</div>
