<?php echo $this->element('record_navigation', array('plugin'=>'core'))?>
<?php 
$html->addCrumb('Pages');
?>
<?php echo $this->element('crumb_heading', array('plugin'=>'core'))?>

<div class="main_no_sidebar">
	<div class="box">
		<div class="box_head">
			<h2><?php __('All pages') ?></h2>
		</div>
		<div class="box_content">
			<?php if(!empty($data)) : ?>
			<?php $data = $nestedMenu->flatten($data); ?>
			<table class="admin_listing">
				<?php foreach($data as $page) : ?>
				<tr>
					<td><?php echo str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;',$page['treeDepth']) ?><?php echo $nav->view(array($page['CorePage']['id']),array('text'=>$page['CorePage']['title'])); ?> </td>
					<td class="actions">
						<?php echo $html->link(__('View',true),array('admin'=>false,'controller'=>'core_pages','action'=>'view',$page['CorePage']['slug'])) ?> 
						<?php echo $nav->moveUp(array($page['CorePage']['id'])); ?> 
						<?php echo $nav->moveDown(array($page['CorePage']['id'])); ?> 
						<?php echo $nav->delete(array($page['CorePage']['id'])); ?> 
					</td>
				</tr>
				<?php endforeach ?> 
			</table>
			<?php endif ?> 
		</div>
	</div>
</div>