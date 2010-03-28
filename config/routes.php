<?php

Router::connect('/pages/*', array('plugin'=>'core', 'controller' => 'core_pages', 'action' => 'view'),array('controller'=>'/^pages$/'));
Router::connect('/admin', array('admin'=>true, 'plugin'=>'core', 'controller' => 'core_dashboard', 'action' => 'index'));

?>