<?php

if(!isset($name)) {
	$name = 'box';
}
if(!isset($count)) {
	$count = 0;
}
if(!isset($title)) {
	$title = "";
}
if(!isset($content)) {
	$content = "";
}
if(!isset($collapsed)) {
	$collapsed = false;
}

?>


	<div class="expandibox" id="<?php echo $name ?>_expander">
		<div class="expandibox_header">
			<div class="expandibox_button expandibox_button_collapsed"></div>
			<div class="expandibox_status_text">
				<?php if(count($data)) echo '('.count($data).')' ?>
			</div>
			<div class="expandibox_header_text">
				<h3><?php echo $title ?></h3>
			</div>
			<div class="clear"></div>
		</div>
		<div class="expandibox_body">

			<?php echo $content ?>

		</div>
	</div>
	<?php 
	

	$collapsed_str = $collapsed ? 'true' : 'false';

	
	echo $javascript->codeblock(<<<END
		$().ready(function() {
			
			$('#{$name}_expander').expandibox({startCollapsed:$collapsed_str});
			
		});
	
END
	);
