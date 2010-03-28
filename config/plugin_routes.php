<?php
foreach(App::objects('plugin') as $plugin) {
	foreach(App::path('plugins') as $path) {
		$routesPath = $path.Inflector::underscore($plugin).DS.'config'.DS.'routes.php';
		if(file_exists($routesPath)) {
			require_once($routesPath);
			break;
		}
	}
}
?>