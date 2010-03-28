<?php echo $layout->heading('Page ' . $page['CorePage']['id'] . ' at ' . $this->passedArgs['date']); ?> 

<div id="main">
	<h3><?php __('Blocks') ?></h3>
	<?php echo $this->element('revision_nav'); ?> 
	<?php if(!empty($page['CoreBlock'])) : ?> 
	<table>
		<?php echo $html->tableHeaders(array('CoreBlock', 'Actions')); ?>
		<?php foreach($page['CoreBlock'] as $block) : ?> 
		<tr>
			<td>
				<?php echo $this->element('block_details', array('block' => $block)); ?>  
			</td>
			<td>
				<?php if(count($block['Revision'] > 1)) : ?> 
				<?php echo $nav->revise(array('model' => 'CoreBlock', 'foreign_key' => $block['id'], 'template' => $block['Revision'][0]['id']), array('text' => __('Restore',true))); ?> 
				<?php endif ?> 
			</td>
		</tr>
		<?php endforeach ?> 
	</table>
	<?php endif ?> 
</div>

<div id="sidebar">
</div>