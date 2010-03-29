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

define('MISSIONCONTROL_PAGE_VIEWS_FOLDER', APP . 'views' . DS . 'pages' . DS);


?>