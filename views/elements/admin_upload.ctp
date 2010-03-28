	<label><?php echo $title ?></label>
<div class="admin_upload_box">

	<div class="file_thumb">
		<?php if (isset($imagePath) && file_exists($imagePath)) : ?> 
			<?php echo $html->image($thumbnailURL) ?>
		<?php else : ?>
			<?php echo $html->image('/core/img/admin/no-image.png') ?>
		<?php endif ?>
	</div>
	<div class="file_inputs">
		<?php if (isset($imagePath)) : ?> 
			<?php echo $form->input($removeFieldName,array('type'=>'checkbox'))?>
		<?php endif?>
		<?php echo $form->file($uploadFieldName) ?>
		<?php echo $form->error($uploadFieldName) ?>
	</div>

	<div class="clear"></div>
</div>