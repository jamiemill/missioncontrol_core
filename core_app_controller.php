<?php
class CoreAppController extends AppController {

	var $components = array(
		'RequestHandler',
		'Auth',
		'Acl',
		'DebugKit.Toolbar',
		'Core.SwiftEmail',
		'Core.Message',
		'Users.UserTools'
	);
	var $helpers = array(
		'Html',
		'Time',
		'Form',
		'Javascript',
		'Session',
		'Text',
		'Core.Layout',
		'Core.Nav',
		'Core.Menu',
		'Core.Permissions',
		'Core.NestedMenu'
	);
	
	/**
	* This is used by child methods to influence the _smartFlash redirect destination	* 
	*/
	
	var $redirectDestination = null;
	
	function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->fields = array('username' => 'email', 'password' => 'password');
		$this->Auth->userScope = array('User.activated' => true, 'User.enabled' => true);
		$this->Auth->authorize = 'controller';
		$this->Auth->actionPath = 'controllers/';
		$this->Auth->userModel = 'Users.User';
		
		if(!$this->Auth->user('id')) {
			$this->Auth->authError = __("Please log in.",true);
		} else {
			$this->Auth->authError = __("Hey! You can't do that!",true);
		}
		
		$this->Auth->loginError = __('Invalid e-mail / password combination.',true);
		$this->Auth->loginAction = array('plugin'=>'users','admin' => true, 'controller' => 'users', 'action' => 'login');
		$this->Auth->logoutRedirect = array('plugin'=>'users','admin' => true, 'controller' => 'users', 'action' => 'login');
		$this->Auth->autoRedirect = false;
		
		$this->Auth->allow('history_state');
		
		if(isset($this->params['admin'])) {
			$this->layout = 'admin';
		}
		if(Configure::read('User.SiteHasFrontendLogin') && !isset($this->params['admin'])) {
			$this->Auth->loginAction = array('plugin'=>'users','admin' => false, 'controller' => 'users', 'action' => 'login');
			$this->Auth->logoutRedirect = array('plugin'=>'users','admin' => false, 'controller' => 'users', 'action' => 'login');
		}
	}
	
	function beforeRender() {
		parent::beforeRender();
		if (!isset ($this->viewVars['modelClass'])) {
			$this->set('modelClass', $this->modelClass);
			if(isset($this->{$this->modelClass})) {
				$this->set('modelDisplayField', $this->{$this->modelClass}->displayField);
			}
		}
	}
	
	function isAuthorized() {
		$Group = ClassRegistry::init('Group');

		$Group->recursive = -1;
		$userGroup = $Group->findById($this->Auth->user('group_id'));
		
		if($this->Acl->check($userGroup, $this->Auth->action())) {
			return true;
		} else {
			return false;
		}
	}
	

	function view($id = null) {
		$model = $this->modelNames[0];
		
		if($id) {
			$item = $this->{$model}->findById($id);
		}
		if(empty($item)) {
			$this->Session->setFlash(__('Invalid item.', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('data', $item);
	}
	
/**
 * Generic 'admin_index' action.
 */		
	function admin_index() {
		$model = $this->modelNames[0];
		
		$this->set('data', $this->paginate());
	}
	
	function admin_add() {
		$model = $this->modelNames[0];
		
		if(!empty($this->data)) { 
			if($this->{$model}->save($this->data)) {
				$this->_smartFlash(true,array('action'=>'view',$this->{$model}->id));
			} else {
				$this->_smartFlash(false);
			}
		}
		
		$this->_findLists($model);
	}
	
	function admin_edit($id) {
		$model = $this->modelNames[0];
		
		if(empty($this->data)) {	
			$this->{$model}->id = $id;
			$this->data = $this->{$model}->read();
		} else {
			if($this->{$model}->save($this->data)) {
				$this->_smartFlash(true,array('action'=>'view',$this->{$model}->id));
			} else {
				$this->_smartFlash(false);
			}
		}
		$this->set('data', $this->{$model}->findById($id));	
		$this->_findLists($model);
	}
	
	function admin_view($id = null) {
		$model = $this->modelNames[0];
		
		if($id) {
			$item = $this->{$model}->findById($id);
		}
		if(empty($item)) {
			$this->Session->setFlash(__('Invalid item.', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('data', $item);	
	}

	
/**
 * Generic 'admin_delete' action.
 * @param int $id The id of the record to be retrieved.
 */
	function admin_delete($id = null, $type = null) {
		$model = $this->modelNames[0];

		$this->{$model}->id = $id;
		
		if($this->{$model}->delete() || $this->{$model}->Behaviors->attached('SoftDeletable')) {
			$this->_smartFlash(true,true);
		} else {
			$this->_smartFlash(false,true);
		}
	}
	
	function index() {
		$model = $this->modelNames[0];
		if(isset($this->params['requested'])) {
			return $this->paginate();
		}
		$this->set('data', $this->paginate());
	}
	
	
/**
 * Grabs lists for the models that the current one belongsTo. For example, if we're at articles:admin_add, this method could be used to automatically get a $users list, which can be used to populate a select box. 
 * This is a little wasteful, blindly doing find('list') for ALL belongsTo associtations. 
 *
 * @param  string  $model   The name of the current model. 
 */
	function _findLists($models) {
		if(!is_array($models)) {
			$models = array($models);
		}
		foreach($models as $model) {
			$Model = ClassRegistry::init($model);
			if(!empty($Model->belongsTo)) {
				foreach($Model->belongsTo as $belongsTo => $params) {
					$this->set(Inflector::variable(Inflector::pluralize($belongsTo)), $Model->{$belongsTo}->find('list'));
				}
			}
		}
	}
	
	function _smartFlash($message = true, $redirect = null) {
		// If a redirect was requested but no specifically declared, use a default one. 
		if($this->redirectDestination) {
			$redirect = $this->redirectDestination;
		} elseif ($redirect === true) {
			$redirect = array('action' => 'index');
		}
		// If a boolean 'message' value was passed in, construct a suitable default message. 
		if($message === true || $message === false) {
			$model = $this->modelNames[0];
		
			$action = str_replace('admin_', '', $this->params['action']);			
			switch($action) {
				case 'add':
					$verb = __('added',true);
					break;
				case 'edit':
					$verb = __('updated',true);
					break;
				case 'delete':
					$verb = __('removed',true);
					break;
				case 'move_up':
					$verb = __('moved up',true);
					break;
				case 'move_down':
					$verb = __('moved down',true);
					break;
				default:
					$verb = __('saved',true);
					break;
			}
				
			if($message === true) {
				$message = Inflector::humanize($model) . ' ' . $verb . '.';
				if(empty($redirect)) {
					$redirect = array('action' => 'index');
				}
			} else {
				$message = sprintf(__('Sorry. That %s could not be %s.',true), low(Inflector::humanize($model)), $verb);
			}
		}
		
		$this->Session->setFlash($message);
		if(!empty($redirect)) {
			$this->redirect($redirect);
		}
	}
}
?>
