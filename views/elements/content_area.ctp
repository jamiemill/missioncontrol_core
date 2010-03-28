<?php

/**
* This element is used to both define, and output the contents of, a Content Area.
* One content area contains many CoreBlocks, and can be thought of as a 'column' of content.
* 
* If the content area has $hidden set to true when it is called, then this area will be available for 
* inserting content within the CMS, but won't be outputted automatically to the screen when the page is rendered.
* This is useful for storing arbitrary content, for instance an 'abstract' of an article which could be 
* outputted on a parent page and not used at all on the actual page.
* 
*/


?>


<?php $blocks = Set::extract('/CoreBlock[content_area=' . $slug . ']', $data); ?> 

<?php if($this->params['action'] == 'admin_layout') : ?> 
	{{<?php echo $slug; ?>}}
<?php elseif(!empty($blocks) && (!isset($hidden) || $hidden == false)) : ?> 
	<?php foreach($blocks as $block) : ?> 
		<?php echo $block['CoreBlock']['content']; ?> 
	<?php endforeach ?> 
<?php endif ?> 
