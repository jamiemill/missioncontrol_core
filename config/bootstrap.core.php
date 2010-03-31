<?php

Configure::write('MissionControl.version', 8);
Configure::write('Site.CMSName', 'MissionControl');

define('USER_GROUP_GUEST',1);
define('USER_GROUP_USER',2);
define('USER_GROUP_CONTRIBUTOR',3);
define('USER_GROUP_EDITOR',4);
define('USER_GROUP_ADMINISTRATOR',5);
define('USER_GROUP_SUPERADMINISTRATOR',6);
define('USER_REGISTER_ACTIVATION_IMMEDIATE','immediate');
define('USER_REGISTER_ACTIVATION_SELF_ACTIVATE','selfActivate');
define('USER_REGISTER_ACTIVATION_ADMIN_NOTIFY','adminNotify');

// with trailing slash
Configure::write('MissionControl.pageViewsFolder', APP . 'views' . DS . 'pages' . DS);

// These should be customised by overriding in {APP}.config/bootstrap.php

Configure::write('Site.title', 'MissionControl Demo Site');
Configure::write('Site.emailsTo','website@example.com');
Configure::write('Site.allowPageParentContent', null); 									
Configure::write('Site.extraThumbnailSizes', array(
	//'thumbnail'=>array(200,200,1,'C'),
	//'homepage'=>array(450,450,1,'C')
));

Configure::write('Robot.fromAddress', 'Example <website@example.com>');
Configure::write('Robot.SMTP.port',null);
Configure::write('Robot.SMTP.type',null);
Configure::write('Robot.SMTP.host',null);
Configure::write('Robot.SMTP.username','website@example.com');
Configure::write('Robot.SMTP.password',null);
Configure::write('SwiftEmail.debug',true);

Configure::write('User.Register.redirect',false);
Configure::write('User.Login.fallbackRedirect','/admin/');
Configure::write('User.SiteHasFrontendLogin',false);


/**
* Pick one of these three constants to define what happens when a user registers at the site:
* 
* 'USER_REGISTER_ACTIVATION_IMMEDIATE'
* 'USER_REGISTER_ACTIVATION_SELF_ACTIVATE'
* 'USER_REGISTER_ACTIVATION_ADMIN_NOTIFY'
*/

Configure::write('User.Register.activation',USER_REGISTER_ACTIVATION_ADMIN_NOTIFY);

?>