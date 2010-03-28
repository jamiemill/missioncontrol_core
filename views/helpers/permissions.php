<?php

class PermissionsHelper extends Helper {

	var $helpers = array('Session');

	function hasPermission($url) {
		
		if(!$this->Session->check('Auth.Permissions')) {
			return false;
		}

		if (!is_array($url)) {
			return false;
		}

		extract($url);

		if (!isset($controller)) {
			$controller = $this->params['controller'];
		}

		$controller = Inflector::camelize($controller);

		if (!isset($action)) {
			$action = $this->params['action'];
		}

		$_admin = Configure::read('Routing.admin');
		
		// this seems to be wrong
		// if ((isset(${$_admin}) && ${$_admin}) || $this->params['action'][$_admin]) {
		// 	$action = $_admin.'_'.$action;
		// }

		$permission = 'controllers/'.$controller.'/'.$action;
		
		return in_array($permission, $this->Session->read('Auth.Permissions'));

	}

}	
 
?>