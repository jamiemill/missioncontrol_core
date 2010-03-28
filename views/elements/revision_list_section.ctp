
<?php 

// in case the data was a single record rather than a list of records

if(!empty($data) && isset($data['Revision'])) {
	$data = array($data);
}

// the empty array returned by an unsucessful single find makes count($data) return 1 if we don't do this

if(empty($data)) {
	$data = null;
}

$selectedFound = false;

if(!isset($hideWhenEmpty)) {
	$hideWhenEmpty = false;
}

if(!$hideWhenEmpty && !empty($data)) :
	
?>

	<div class="expandibox" id="<?php echo $name ?>_expander">
		<div class="expandibox_header">
			<div class="expandibox_button expandibox_button_collapsed"></div>
			<div class="expandibox_status_text">
				<?php if(count($data)) echo '('.count($data).')' ?>
			</div>
			<div class="expandibox_header_text">
				<h3><?php echo $titleBarContent ?></h3>
			</div>
			<div class="clear"></div>
		</div>
		<div class="expandibox_body">


	<?php if(!empty($data)) : ?>

		<ul class="revision_list">
		
			<?php foreach ($data as $revision) : ?>

				<?php
				if($selectedRevision['Revision']['id'] == $revision['Revision']['id']) {
					$itemClass = 'selected_revision';
					$selectedFound = true;
				} 
				else {
					$itemClass = 'unselected_revision';
				}
		
				?>
		
		<?php if($revision['Revision']['status'] == 'approved') : ?>
		
				<li class="<?php echo $itemClass ?>"><?php echo $html->link(
			
						'#' 
						. $revision['Revision']['id'] 
						. '. '
						. ' by '
						. $revision['User']['name'] 
						//. '<br />'
						. ' <span class="revisionInfo">published: '
						. $time->niceShort($revision['Revision']['publish_time']) 
						.'</span>'
				
					,'/admin/pages/edit/'.$page_data['CorePage']['id'].'/revision:'.$revision['Revision']['id'],null,null,false)  ?></li>
		
		<?php else : ?>
			
			<li class="<?php echo $itemClass ?>"><?php echo $html->link(
		
					'#' 
					. $revision['Revision']['id'] 
					. '. '
					. ' by '
					. $revision['User']['name'] 
					//. '<br />'
					. ' <span class="revisionInfo">created: '
					. $time->niceShort($revision['Revision']['created']) 
					.'</span>'
			
				,'/admin/pages/edit/'.$page_data['CorePage']['id'].'/revision:'.$revision['Revision']['id'],null,null,false)  ?></li>
			
		<?php endif ?>

			<?php endforeach ?>

		</ul>
	
	<?php else : ?>
	
		<p><?php echo $ifEmptyContent ?></p>
	
	<?php endif ?>


		</div>
	</div>
	<?php 
	
	if(!isset($startCollapsed) && empty($data)) {
		$startCollapsed = true;
	}
	elseif(!isset($startCollapsed)) {
		$startCollapsed = false;
	}
	
	//TODO: now ignoring the startCollapsed option and deciding whether to start collapsed based on whether or not this list contains the selected Item. should update the view file too.
	//$start_str = $startCollapsed ? 'true' : 'false';
	$start_str = !$selectedFound ? 'true' : 'false';
	

	
	
	
	echo $javascript->codeblock(<<<END
		window.addEvent('domready', function() { 
	var blogBox = new ExpandiBox('{$name}_expander',{startCollapsed:$start_str});
	});	
END
	);

endif;

?>