<?php

require_once(APP.'plugins'.DS.'core'.DS.'tests'.DS.'cases'.DS.'abstract'.DS.'mission_control_test_case.php');

/**
* Testcase used to iron out the extend testcase used to automatically populate a fixtures variables
**/

class DummyTestCase extends MissionControlTestCase {

}

class MissionControlTestCaseTestCase extends CakeTestCase {

	function testCanCreateMissionControlTestCase() {
		$this->mctc = new MissionControlTestCase();
		$this->assertIsA($this->mctc,'CakeTestCase');
	}
	
	// function testCanGetDirectoryIterator() {
	// 	$dirIterator = new DirectoryIterator(dirname(__FILE__) .'/../../fixtures/');
	// 	$this->assertIsA($dirIterator, 'DirectoryIterator');
	// }
	
	function testCanIterateOverFixtures() {
		$this->assertNotEqual($this->mctc->fixtures, array());
		$this->assertTrue(count($this->mctc->fixtures) > 0);
		foreach( $this->mctc->fixtures as $fixture) {
			$this->assertNotNull($fixture);
		}
	}
	
	function testCanRetrieveAListOfFixtures() {
		$this->assertIsA($this->mctc->fixtures,'Array');
	}
	
	function testCanRetrieveAListOfFixturesWithAnAppPrefix() {
		foreach( $this->mctc->fixtures as $fixture) {
			$this->assertPattern('/^app\./',$fixture);
		}
	}
	
	function testCanAssignFoundFixturesToTheClassesFixturesVariable() {
		$this->assertNotNull($this->mctc->fixtures);
	}
	
	function testMissionControlTestCaseCanBeExtended() {
		$dummyTest = new DummyTestCase();
		$this->assertIsA($dummyTest, 'MissionControlTestCase');
	}
	
	function testDummyTestCaseHasImportedListOfSystemFixtures(){
		$dummyTest = new DummyTestCase();
		$this->assertNotEqual(array(),$dummyTest->fixtures);
	}
}
?>