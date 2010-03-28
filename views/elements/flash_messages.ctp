<?php if($session->check('Message.flash')) : ?>
<div class="flash_message">
	<?php echo $session->flash(); ?>
</div>
<?php endif ?>
<?php if($session->check('Message.auth')) : ?>
<div class="flash_message">
	<?php echo $session->flash('auth'); ?>
</div>
<?php endif ?>