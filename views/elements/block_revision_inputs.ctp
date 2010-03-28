		<?php echo $form->hidden('user_id'); ?> 
		<?php echo $form->hidden('parent_id'); ?> 
		<?php echo $form->hidden('model', array('value' => $this->passedArgs['model'])); ?> 
		<?php echo $form->hidden('foreign_key', array('value' => $this->passedArgs['foreign_key'])); ?> 
		<?php echo $form->hidden('content_area'); ?> 
		<?php echo $form->hidden('order'); ?> 
		<?php echo $form->hidden('status'); ?> 
		<?php echo $form->hidden('publish_time'); ?> 
		<?php echo $form->input('title') ?> 
		<?php echo $form->input('content') ?> 
