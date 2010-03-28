		<h3><?php __('Page Options') ?></h3>
		<?php echo $form->input('title') ?> 
		<?php echo $form->input('parent_id', array('type' => 'select', 'options' => @$pages, 'empty' => '- '.__('none (this is a root page)',true).' -')) ?>
		<?php echo $form->input('show_in_menu', array('label' => __('Show in menu?',true), 'type' => 'checkbox')) ?>
		<?php echo $form->input('is_published', array('label' => __('Published?',true), 'type' => 'checkbox')) ?>
		<?php echo $form->input('publish_time'); ?> 
		<?php echo $form->submit(__('Save changes',true),array('name'=>'data[submit]')) ?> 
		
		<h3><?php __('Advanced Options') ?></h3>
		<?php echo $form->input('slug') ?>
		<?php echo $form->input('title_long') ?> 
		<?php echo $form->input('meta_description') ?> 
		<?php echo $form->input('meta_keywords') ?> 
		<?php echo $form->input('view_file', array('empty' => 'Unassigned')) ?> 
		<?php echo $form->input('layout_file') ?> 
		<?php echo $form->input('redirect_to_first_child') ?> 
		<?php echo $form->input('is_protected', array('label' => __('Protect from delete?',true), 'type' => 'checkbox')) ?> 

		<?php echo $form->submit(__('Save changes',true), array('name'=>'data[submit]')) ?> 
