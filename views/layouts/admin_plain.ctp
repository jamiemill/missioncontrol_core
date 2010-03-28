<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<?php echo $html->charset(); ?> 
	<?php echo $html->css('/core/css/reset'); ?> 
	<?php echo $html->css('/core/css/admin'); ?> 
	<?php echo $html->css('/core/css/admin_skin'); ?> 
	<?php echo $html->script('/core/js/jquery-1.3.2.min.js'); ?> 
	<?php echo $html->script('/core/js/jquery-ui-1.7.2.min'); ?>
	<?php echo $html->script('/core/js/jquery.expandibox.js'); ?>
	<!--[if IE]>
		<?php echo $html->script('/core/js/jquery.corner.js'); ?>
		<?php echo $html->script('/core/js/init-corners.js'); ?>
	<![endif]-->
	<?php echo $html->script('tiny_mce/jquery.tinymce'); ?> 
	<?php echo $html->script('/core/js/global.js'); ?>
	<?php 
	$base = $html->url('/');
	echo $html->scriptBlock(<<<END
		baseURL = '$base';
END
)?>
	<?php echo $scripts_for_layout; ?>
	
	<title><?php echo Configure::read('Site.CMSName') ?> : <?php echo Configure::read('Site.title') ?> : <?php echo $title_for_layout; ?></title>
		
</head>

<body class="plain">
	<div id="head">
		<div class="content">
			<?php echo $this->element('login_info',array('plugin'=>'core')) ?>
		</div>
		<div class="clear"></div>
	</div>
	<div id="body">

		<div class="content">
		
			<div class="flash_message">
				<?php
					if ($session->check('Message.flash')) {
						echo $session->flash();
					}
					if ($session->check('Message.auth')) {
						echo $session->flash('auth');
					}
				?>
			</div>
	
			<?php echo $content_for_layout ?>
		</div>
	</div>
	

	
</body>
</html>
