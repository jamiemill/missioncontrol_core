<?php

/**
* Note that instantiating model should be done with ClassRegistry::init() instead of the 'new' 
* keyword, otherwise it seems the wrong database connection is used for those models
* 
*/

App::import('core','Folder');

class MissionControlTestCase extends CakeTestCase {
	
	function __construct() {
		$this->fixtures = $this->getFixtures();
		parent::__construct();
	}
	
	function getFixtures() {
		$fixtures = array();
		$folder = new Folder(APP.'tests'.DS.'fixtures');
		$files = $folder->find('.*_fixture\.php$');
		foreach( $files as $file) {
			$fixture = str_replace('_fixture.php','',$file);
			$fixtures[] = 'app.' .str_replace('._','',$fixture);
		}
		return $fixtures;
	}
	
}

?>