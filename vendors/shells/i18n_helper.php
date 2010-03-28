<?php

App::import(array('Model', 'AppModel', 'File'));

class I18nHelperShell extends Shell{
	
	var $tasks = array('AppExtract');
	
	var $ignoreFields = array(
		'id',
		'lft',
		'rght',
		'geolat',
		'geolon',
		'slug'
	);
	
	/**
	* Some of the following are specific to ClubMonk which I am working on at the moment
	* Need to find away to ignore on an app-by-app basis
	*/
	
	var $ignoreTables = array(
		'acos',
		'aros',
		'aros_acos',
		'search_indices',
		'search_index',
		'tickets',
		'core_pages',
		'core_blocks',
		'core_blocks_revs',
		'core_block_types',
		'core_search_index',
		'countries',
		'instant_payment_notifications'
	);
	
	function main() {
		if ($this->args && $this->args[0] == '?') {
			return $this->out('Usage: ./cake i18n_helper <command>');
		}
		$options = array(
			// 'force' => false,
			// 			'reindex' => false,
			// 			'all' => false,
		);
		foreach ($this->params as $key => $val) {
			foreach ($options as $name => $option) {
				if (isset($this->params[$name]) || isset($this->params['-'.$name]) || isset($this->params[$name{0}])) {
					$options[$name] = true;
				}
			}
		}
		//debug($this->params)
		
		if($this->args[0] == 'dummies') {
			$this->generateDummyFieldNames();
		} elseif ($this->args[0] == 'extract') {
			$this->extract();
		}
	
	}
	
	function generateDummyFieldNames() {
		
		$db = ConnectionManager::getDataSource('default');
		
		$this->tables = $db->listSources();
		
		$out = array();
		
		foreach($this->tables as $table) {
			if(in_array($table, $this->ignoreTables)) {
				continue;
			}
			$fields = $db->describe($table);
			foreach($fields as $fieldName => $meta) {
				if(in_array($fieldName, $this->ignoreFields)) {
					continue;
				}
				$label = $this->__labelizeFieldName($fieldName);
				$out[] = "<?php __('".$label."') ?>";
			}
		}

		$file = APP.'locale'.DS.'dummy_database_fields_for_translation.php';
		$File = new File($file);
		
		$File->write(join("\n", $out));
		$this->out('done.');

	}
	
	function extract() {
		$this->AppExtract->execute();
	}
	
	function __labelizeFieldName($text) {
		if (substr($text, -3) == '_id') {
			$text = substr($text, 0, strlen($text) - 3);
		}
		$text = Inflector::humanize(Inflector::underscore($text));
		return $text;
	}
}

?>