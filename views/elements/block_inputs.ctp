		<?php echo $form->input('core_page_id', array('type'=>'hidden')); ?> 
		
		<?php echo $form->hidden('user_id'); ?> 

		<?php echo $form->input('content', array('class' => 'tinymce')) ?>
		
		<?php echo $form->input('content_area', array('options' => $contentAreas, 'empty' => '- '.__('unassigned',true).' -')); ?> 