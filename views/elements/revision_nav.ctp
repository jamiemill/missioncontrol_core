	<?php echo $form->create('CorePage', array('url' => '/'.$this->params['url']['url'])); ?> 
		<?php echo $form->input('revision_date', array('type' => 'date', 'selected' => @$this->passedArgs['date'])); ?> 
	<?php echo $form->end('Go'); ?> 
