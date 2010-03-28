<?php 
class NavHelper extends AppHelper {

	var $helpers = array('Html', 'Session');

/**
 * Returns the specified breadcrumb trail as a series of links. Has a stab at guessing what the breadcrumbs are if they aren't provided.
 *
 * @param  array   $crumbs The links to be included.
 * @param  string  $separator Text to separate crumbs.
 * @param  string  $startText This will be the first crumb, if false it defaults to first crumb in array
 * @return string
 */
	function crumbs($crumbs, $separator = ' &raquo; ', $startText = false) {
		// Set some defaults.
		if(empty($options['before'])) {
			$options['before'] = 'You are Here: ';
		}
		if(empty($options['after'])) {
			$options['after'] = null;
		}
		
		// Generate a default breadcrumb trail if one hasn't been explicitly set. Just use the current controller and action. 
		if(empty($crumbs)) {
			// If we're on an admin route, prefix the crumbs with that.
			if(isset($this->params['admin']) && $this->params['admin'] == 1) {
				$crumbs[] = array('Admin');
			}
			
			$action = str_replace('admin_', '', $this->params['action']);
			if($action == 'index') { 
				$crumbs[] = array(Inflector::humanize($this->params['controller']));
			} else if($this->params['url']['url'] == '/') {
				$crumbs[] = array('Home');
			} else {
				$crumbs[] = array(Inflector::humanize($this->params['controller']), array('admin' => false, 'action' => 'index'));
				$crumbs[] = array(Inflector::humanize($action));
			}
		}
		
		$out = array();
		if ($startText) {
			$out[] = $this->Html->link($startText, '/');
		}

		foreach ($crumbs as $crumb) {
			if (!empty($crumb[1])) {
				$out[] = $this->Html->link($crumb[0], @$crumb[1], @$crumb[2]);
			} else {
				$out[] = $crumb[0];
			}			
		}
		$out = $options['before'] . $this->output(join($separator, $out)) . $options['after'];
		return $this->Html->para('crumbs', $out);
	}

/**
 * Returns a navigation <li> link. Returns an 'active' class if the the link's controller with the class set to active where suitable.
 *
 * @param  string  $url The location to link to.
 * @param  array   $options Extra options. 
 * @return string
 */
	function link($name, $url, $options = array()) {
		// If parts of the URL haven't been specified, set some defaults. 
		if(!isset($url['controller'])) {
			$url['controller'] = $this->params['controller'];
		}
		if(!isset($url['action'])) {
			$url['action'] = $this->params['action'];
		}
		if(!isset($options['htmlAttributes'])) {
			$options['htmlAttributes'] = null;
		}

		if(!empty($options['match'])) {
			foreach($options['match'] as $linkSnippet => $otherSnippet) {
				if($linkSnippet == $otherSnippet) {
					if(!empty($options['htmlAttributes']['class'])) {
						$options['htmlAttributes']['class'] .= ' active';
					} else {
						$options['htmlAttributes']['class'] = 'active';
					}
				}
			}
		}
		
		return $this->Html->link($name, $url, $options['htmlAttributes'], false, false);
	}
	
/**
 * Returns a 'back to' link.
 *
 * @param  string  $url The location to link to.
 * @param  array   $options Extra options. 
 * @return string
 */
	function back($url = null, $options = array()) {
		if(empty($options['before'])) {
			$options['before'] = '&laquo; ';
		}
		
		if(empty($url)) {
			if(isset($this->params['admin']) && ($this->params['action'] == 'admin_add' || $this->params['action'] == 'admin_edit' || $this->params['action'] == 'admin_view')) {
				$url = array('action' => 'index');
				if(empty($options['text'])) {
					$options['text'] = Inflector::humanize($this->params['controller']);
				}
			} else if(isset($this->params['admin'])) {
				$url = array('controller' => 'dashboard', 'action' => 'index');
				if(empty($options['text'])) {
					$options['text'] = 'Dashboard';
				}
			} else if($this->params['action'] == 'view') {
				$url = array('action' => 'index');
				if(empty($options['text'])) {
					$options['text'] = ucfirst(Inflector::humanize($this->params['controller']));
				}
			} else {
				$url = '/';
				if(empty($options['text'])) {
					$options['text'] = 'Home';
				}
			}
		}
		
		// Set some default text if none was specified.
		if(empty($options['text'])) {
			if(!empty($url['controller'])) {
				$options['text'] = Inflector::humanize($url['controller']);
			} else {
				$options['text'] = Inflector::humanize($this->params['controller']);
			}
		}		
		
		if(!empty($options['text'])) {
			$options['text'] = '<em>' . $options['text'] . '</em>';
		}
		
		return $this->Html->para('back', $this->Html->link($options['before'] . $options['text'], $url, array('escape'=>false)));
	}
	
/**
 * Returns an 'add' link for the current controller.
 *
 * @param  array   $options Output options. 
 * @return string 'Add' link
 */
	function add($url = null, $options = array()) {		
		// Set some defaults.
		if(empty($url['admin'])) {
			$url['admin'] = true;
		}
		if(empty($url['action'])) {
			$url['action'] = 'add';
		}
		if(empty($options['text'])) {
			$options['text'] = 'Add';
		}

		if(empty($options['thing'])) {
			if(!empty($url['controller'])) {
				$options['thing'] = Inflector::humanize(Inflector::singularize($url['controller']));
			} else {
				$options['thing'] = Inflector::humanize(Inflector::singularize($this->params['controller']));
			}
		}
		return $this->Html->link($options['text'], $url, array('title' => 'Add a new ' . $options['thing'], 'class' => 'action add'));
	}

/**
 * Returns an 'edit' link for the current controller.
 *
 * @param  array   $params Output options. 
 * @return string 'Edit' link
 */
	function edit($url = null, $options = array()) {
		// Set some defaults.
		if(empty($url['admin'])) {
			$url['admin'] = true;
		}
		if(empty($url['action'])) {
			$url['action'] = 'edit';
		}
		if(empty($options['text'])) {
			$options['text'] = 'Edit';
		}
		
		return $this->Html->link($options['text'], $url, array('title' => 'Edit this entry', 'class' => 'action edit'));
	}
	
/**
 * Returns an 'edit' link for the current controller.
 *
 * @param  array   $params Output options. 
 * @return string 'Edit' link
 */
	function view($url = null, $options = array()) {
		// Set some defaults.
		if(empty($url['admin'])) {
			$url['admin'] = true;
		}
		if(empty($url['action'])) {
			$url['action'] = 'view';
		}
		if(empty($options['text'])) {
			$options['text'] = 'View';
		}
		
		return $this->Html->link($options['text'], $url, array('title' => 'View this entry', 'class' => 'action view'));
	}
	
/**
 * Returns a 'revise' link for the current controller.
 *
 * @param  array   $params Output options. 
 * @return string 'Revise' link
 */
	function revise($url = null, $options = array()) {
		// Set some defaults.
		if(empty($url['admin'])) {
			$url['admin'] = true;
		}
		if(empty($url['controller'])) {
			$url['controller'] = 'revisions';
		}
		if(empty($url['model'])) {
			$url['model'] = Inflector::classify($this->params['controller']);
		}
		if(empty($url['action'])) {
			$url['action'] = 'add';
		}
		if(empty($options['text'])) {
			$options['text'] = 'Revise';
		}
		
		return $this->Html->link($options['text'], $url, array('title' => 'Revise this entry', 'class' => 'action revise'));
	}
	
/**
 * Returns a 'move up' link for the current controller.
 *
 * @param  array   $params Output options. 
 * @return string 'Move up' link
 */
	function moveUp($url = null, $options = array()) {
		// Set some defaults.
		if(empty($url['admin'])) {
			$url['admin'] = true;
		}
		if(empty($url['action'])) {
			$url['action'] = 'move_up';
		}
		if(empty($options['text'])) {
			$options['text'] = 'Move up';
		}
		
		return $this->Html->link($options['text'], $url, array('title' => 'Move this entry up', 'class' => 'action move_up'));
	}
	
/**
 * Returns a 'move down' link for the current controller.
 *
 * @param  array   $params Output options. 
 * @return string 'Move down' link
 */
	function moveDown($url = null, $options = array()) {
		// Set some defaults.
		if(empty($url['admin'])) {
			$url['admin'] = true;
		}
		if(empty($url['action'])) {
			$url['action'] = 'move_down';
		}
		if(empty($options['text'])) {
			$options['text'] = 'Move down';
		}
		
		return $this->Html->link($options['text'], $url, array('title' => 'Move this entry down', 'class' => 'action move_down'));
	}
	
/**
 * Returns a 'history' link for the current controller.
 *
 * @param  array   $url The URL to go to. 
 * @return string 'View' link
 */
	function history($url = null, $options = array()) {
		// Set some defaults.
		if(empty($url['admin'])) {
			$url['admin'] = true;
		}
		if(empty($url['action'])) {
			$url['action'] = 'history';
		}
		if(empty($options['text'])) {
			$options['text'] = 'Revision History';
		}
		
		return $this->Html->link($options['text'], $url, array('title' => $options['text'], 'class' => 'action history'));
	}
	
/**
 * Returns a 'delete' link for the current controller.
 *
 * @param  array   $params Output options. 
 * @return string 'Delete' link
 */
	function delete($url = null, $options = array()) {
		// Set some defaults.
		if(empty($url['admin'])) {
			$url['admin'] = true;
		}
		if(empty($url['action'])) {
			$url['action'] = 'delete';
		}
		if(empty($options['text'])) {
			$options['text'] = 'Delete';
		}
		if(empty($options['confirmMessage'])) {
			$options['confirmMessage'] = 'Are you sure you want to delete that entry?';
		}
		
		return $this->Html->link($options['text'], $url, array('title' => $options['text'], 'class' => 'action delete'), $options['confirmMessage']);
	}
	
/**
 * Returns a 'restore' link for the current controller.
 *
 * @param  array   $params Output options. 
 * @return string 'Delete' link
 */
	function restore($url = null, $options = array()) {
		// Set some defaults.
		if(empty($url['admin'])) {
			$url['admin'] = true;
		}
		if(empty($url['action'])) {
			$url['action'] = 'restore';
		}
		if(empty($options['text'])) {
			$options['text'] = 'Restore';
		}
		if(empty($options['confirmMessage'])) {
			$options['confirmMessage'] = 'Are you sure you want to restore that entry?';
		}
		
		return $this->Html->link($options['text'], $url, array('title' => $options['text'], 'class' => 'action restore'), $options['confirmMessage']);
	}
	
/**
 * Returns a 'manage' link for the current controller.
 *
 * @param  array   $params Output options. 
 * @return string 'Delete' link
 */
	function manage($url = null, $options = array()) {
		if(empty($url['admin'])) {
			$url['admin'] = true;
		}
		if(empty($url['action'])) {
			$url['action'] = 'password';
		}
		if(empty($options['text'])) {
			if(!empty($url['controller'])) {
				$thing = Inflector::humanize(Inflector::singularize($url['controller']));
			} else {
				$thing = Inflector::humanize(Inflector::singularize($this->params['controller']));
			}
			$options['text'] = 'Manage ' . $thing;
		}
		
		return $this->Html->link($options['text'], $url, array('title' => $options['text'], 'class' => 'action manage'));
	}
}
?>
