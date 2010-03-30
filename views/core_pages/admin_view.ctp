<?php echo $this->element('record_navigation', array('plugin'=>'core'))?>
<?php 
$html->addCrumb(__('Pages',true),array('controller'=>'core_pages','action'=>'index'));
$html->addCrumb($data['CorePage']['title']);
?>
<?php echo $this->element('crumb_heading', array('plugin'=>'core'))?>

<div class="main">
			<?php if($data['CorePage']['redirect_to_first_child']) : ?>
				<div class="notice"><?php __('This page is set to redirect straight to its first child. You cannot add any content to this page.') ?></div>
			<?php elseif(empty($contentAreas)) : ?>
				<div class="notice"><?php __('This template for this page has no content areas. You can add blocks but they won\'t be visible until you choose a view template which contains content areas.')?></div>
			<?php endif ?>
				
			<?php if(!empty($contentAreas)) : ?>
				<?php foreach($contentAreas as $contentArea) :?>
				<?php $blocks = Set::extract('/CoreBlock[content_area=' . $contentArea . ']', $data); ?> 
					<div class="box">
						<div class="box_head">
							<div class="box_actions"><?php echo $nav->add(array('controller' => 'core_blocks', 'core_page_id' => $data['CorePage']['id'],'content_area'=>$contentArea),array('text'=>'+ '.__('add block',true))); ?></div>
							<h2><?php echo $contentArea?></h2>
						</div>
						<div class="box_content">
							<ul>
							<?php foreach($blocks as $block) : ?>
								<li class="block">
									<div class="actions">
										<?php echo $nav->edit(array('controller' => 'core_blocks', $block['CoreBlock']['id'])); ?><br />
										<?php echo $nav->delete(array('controller' => 'core_blocks', $block['CoreBlock']['id'])); ?><br />
										<?php echo $nav->history(array('controller' => 'core_blocks', $block['CoreBlock']['id']), array('text' => __('History',true))); ?> 
									</div>
									<?php echo $this->element('block_details', array('block' => $block['CoreBlock'])); ?>
									<div class="clear"></div>
								</li>
							<?php endforeach ?>
							</ul>
						</div>
					</div>
				<?php endforeach ?>
			<?php endif ?>
				<?php
	
				// it would be nice if this worked - but I don't think Set::extract supports negation - can we move the negation inside the regex somehow?
				//$search = '/CoreBlock[content_area!=/^(' . implode('|',$contentAreas) . ')$/]';
				//$orphaned = Set::extract($search, $data);
	
				$orphaned = array();
				if(empty($contentAreas)) {
					foreach($data['CoreBlock'] as $block) {
						$orphaned[] = array('CoreBlock'=>$block);
					}
				} else {
					$contentAreasRegex = '/^(' . implode('|',$contentAreas) . ')$/';
					foreach($data['CoreBlock'] as $block) {
						if(!preg_match($contentAreasRegex, $block['content_area'])) {
							$orphaned[] = array('CoreBlock'=>$block);
						}
					}
				}
	
				?>
	
				<?php if(!empty($orphaned)) : ?>
					<div class="box">
						<div class="box_head">
							<h2><?php __('unassigned') ?></h2>
						</div>
						<div class="box_content">
							<div class="notice"><?php __('The following blocks will not be visible because they are not assigned to a content area. Perhaps you changed the page\'s template to one which didn\'t have the same content areas?') ?></div>
							<ul>
							<?php foreach($orphaned as $block) : ?>
								<li class="block">
									<div class="actions">
										<?php echo $nav->edit(array('controller' => 'core_blocks', $block['CoreBlock']['id'])); ?><br />
										<?php echo $nav->delete(array('controller' => 'core_blocks', $block['CoreBlock']['id'])); ?><br />
										<?php echo $nav->history(array('controller' => 'core_blocks', $block['CoreBlock']['id']), array('text' => __('History',true))); ?> 
									</div>
									<?php echo $this->element('block_details', array('block' => $block['CoreBlock'])); ?>
									<div class="clear"></div>
								</li>
							<?php endforeach ?>
							</ul>
						</div>
					</div>
				<?php endif ?>

</div>

<div class="sidebar">
	<div class="box">
		<div class="box_head">
			<div class="box_actions">
				<?php echo $nav->edit(array($data['CorePage']['id']),array('text'=>'edit')); ?>
			</div>
			<h2><?php __('Page settings') ?></h2>
		</div>
		<div class="box_content">
			<table>
				<tr><td><?php __('Title') ?>:</td><td><?php echo $data['CorePage']['title']?></td></tr>
				<tr><td><?php __('Show in menu') ?>:</td><td><?php echo $this->element('boolean_image',array('value'=>$data['CorePage']['show_in_menu']))?></td></tr>
				<tr><td><?php __('Published') ?>:</td><td><?php echo $this->element('boolean_image',array('value'=>$data['CorePage']['is_published']))?></td></tr>
				<tr><td><?php __('Publish time') ?>:</td><td><?php echo $data['CorePage']['publish_time']?></td></tr>
				<tr><td><?php __('Slug') ?>:</td><td><?php echo $data['CorePage']['slug']?></td></tr>
				<tr><td><?php __('Long title') ?>:</td><td><?php echo $data['CorePage']['title_long']?></td></tr>
				<tr><td><?php __('Meta description') ?>:</td><td><?php echo $data['CorePage']['meta_description']?></td></tr>
				<tr><td><?php __('Meta keywords') ?>:</td><td><?php echo $data['CorePage']['meta_keywords']?></td></tr>
				<tr><td><?php __('View template') ?>:</td><td><?php echo $data['CorePage']['view_file']?></td></tr>
				<tr><td><?php __('Layout template') ?>:</td><td><?php echo $data['CorePage']['layout_file']?></td></tr>
				<tr><td><?php __('Redirect to first child') ?>:</td><td><?php echo $this->element('boolean_image',array('value'=>$data['CorePage']['redirect_to_first_child']))?></td></tr>
				<tr><td><?php __('Protect from delete') ?>:</td><td><?php echo $this->element('boolean_image',array('value'=>$data['CorePage']['is_protected']))?></td></tr>
			</table>
		</div>
	</div>
	<div class="box">
		<div class="box_head">
			<div class="box_actions">
				<?php echo $html->link('+ add', array('admin'=>true,'plugin'=>'core','controller'=>'core_custom_fields','action'=>'add','core_page_id'=>$data['CorePage']['id'])) ?>
			</div>
			<h2><?php __('Custom Fields') ?></h2>
		</div>
		<div class="box_content">
			<table class="admin_listing admin_view">
			<?php foreach($data['CoreCustomField'] as $field) : ?>
				<tr>
					<td><?php echo $field['name']?></td>
					<td><?php echo $field['value']?></td>
					<td>
						<?php echo $html->link('edit',array('controller'=>'core_custom_fields','action'=>'edit',$field['id']))?>
						<?php echo $html->link('delete',array('controller'=>'core_custom_fields','action'=>'delete',$field['id']),null,'Are you sure?')?>
					</td>
				</tr>
			<?php endforeach ?>
			</table>
		</div>
	</div>
</div>
