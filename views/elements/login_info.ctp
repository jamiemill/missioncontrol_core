<div class="login_info">
	
	<?php if(Configure::read('debug')) : ?>
		<span class="warningtext"><?php __('Debug mode enabled') ?></span>
	<?php endif ?>
	
	<?php if($session->check('Auth.Users.User')) : ?>	
			
		<?php echo sprintf(__('Logged in as %s %s',true), $session->read('Auth.Users.User.first_name').' '.$session->read('Auth.Users.User.last_name'), $session->read('Auth.User.last_name')) ?> |
			
		<?php if($session->read('Auth.Users.User.group_id') >= USER_GROUP_CONTRIBUTOR) : ?>
			
			<?php echo $html->link(__('Homepage',true),'/') ?> |
			<?php echo $html->link(__('Dashboard',true),'/admin/') ?> |
			
		<?php endif ?>
		
		<?php echo $html->link(__('Log out',true),'/users/logout') ?>
		
	<?php endif ?>
</div>
