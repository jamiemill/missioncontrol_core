<?php


App::import('Component', 'Acl');
App::import('Model', 'DbAcl');

/**
 * Shell for setting the default permissions
 *
 */
class PermissionsShell extends Shell {
	
/**
* Uses the AppPermissions Task to set permissions that are specific to each app.
* This task can be overridden in each application
*/
	var $tasks;
	
/**
 * Contains instance of AclComponent
 *
 * @var object
 * @access public
 */
	var $Acl;

/**
 * Contains arguments parsed from the command line.
 *
 * @var array
 * @access public
 */
	var $args;

/**
 * Contains database source to use
 *
 * @var string
 * @access public
 */
	var $dataSource = 'default';

/**
 * Root node name.
 *
 * @var string
 **/
	var $rootNode = 'controllers';

/**
 * Internal Clean Actions switch
 *
 * @var boolean
 **/
	var $_clean = false;

/**
 * Start up And load Acl Component / Aco model
 *
 * @return void
 **/
	function startup() {
		$this->Acl =& new AclComponent();
		$controller = null;
		$this->Acl->startup($controller);
		$this->Aro =& $this->Acl->Aro;
		
		$plugins = Configure::listObjects('plugin');
		$plugins[] = 'App'; // include the non-plugin task if it exists
		foreach($plugins as  $plugin) {
			foreach ($this->Dispatch->shellPaths as $path) {
				$taskPath = $path . 'tasks' . DS . Inflector::underscore($plugin).'_permissions.php';
				if (file_exists($taskPath)) {
					$this->tasks[] = $plugin.'Permissions';
					break;
				}
			}
		}
		$this->loadTasks();
	}

/**
 * Override main() for help message hook
 *
 * @access public
 */
	function main() {
		$out  = __("Available commands:", true) . "\n";
		$out .= "\t - reset\n";
		$out .= "\t - rebuild_aros\n";
		$this->out($out);
	}

/**
 * Deletes all permissions and resets to defaults using this application's AppPermissionsTask plus any available {Plugin}PermissionsTasks
 *
 * @return void
 **/
	function reset() {
		
		$this->out(__("Resetting...", true));
		
		foreach($this->tasks as $task) {
			$this->hr();
			$this->out('Including '.$task."Task :");
			$this->hr();
			$this->{$task}->Aro =& $this->Aro;
			$this->{$task}->Acl =& $this->Acl;
			$this->{$task}->execute();
		}
		
		$this->out(__('Reset Complete.', true));
	}
	
	function rebuild_aros() {
		$this->out(__('Rebuilding aros from Groups...', true));
		
		$this->Aro->query('truncate table `aros`;');
		
		App::import('model',array('Users.Group'));
		$Group = ClassRegistry::init('Group');
		
		$groups = $Group->find('all',array('recursive'=>-1));
		foreach($groups as $group) {
			$Group->id = $group['Group']['id'];
			$this->_checkNodeAndCreateIfNecessary($Group);
		}
		
		$this->out(__('Done.', true));
	}
	
	function _checkNodeAndCreateIfNecessary(&$Model) {
		@$node = $Model->node();
		if(!$node) {
			$data = array('parent_id'=>null,'model'=>$Model->name,'foreign_key'=>$Model->id);
			$this->Aro->create();
			$node = $this->Aro->save($data);
			$Model->data = null;
			$node['Aro']['id'] = $this->Aro->id;
			$this->out(sprintf(__('Created Aro node: %s', true), $Model->name.'.'.$Model->id));

			$parentNode = $Model->node($Model->parentNode());
			if($parentNode) {
				$node['Aro']['parent_id'] = $parentNode[0]['Aro']['id'];
				unset($node['Aro']['lft']);
				unset($node['Aro']['rght']);
				$this->Aro->id = $node['Aro']['id'];
				if($this->Aro->save($node)) {
					$this->out(sprintf(__('Set Aro parent_id to: %s', true), $node['Aro']['parent_id']));
				} else {
					// TODO: there was not necessarily a problem - probably just has no parent... find out why we're getting to here
					$this->out(__('Problem reparenting Aro', true));
				}	
			}
		}
	}
	
}
?>