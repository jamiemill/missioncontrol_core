<?php  

/*
 * EXAMPLE:
 * 
 * $menu->menu(array( 
 *		'Dashboard' => array(
 *			'route'=>array('controller' => 'accounts', 'action'=>'dashboard'),
 * 			'match'=>"^{$base}accounts/dashboard"), 
 *		'Projects' => array(
 *			'route'=>array('controller' => 'projects'),
 *			'match'=>"^{$base}projects")
 *		...
 *		), 
 *		array('class' => 'submenu')
 *	);
 * 
 */

class MenuHelper extends AppHelper 
{ 
    var $helpers = array('Html','Core.Permissions'); 
     
    function menu($links = array(),$htmlAttributes = array(),$checkPermissions = false, $type = 'ul') {       
        $this->tags['ul'] = '<ul%s>%s</ul>'; 
        $this->tags['ol'] = '<ol%s>%s</ol>'; 
        $this->tags['li'] = '<li%s>%s</li>'; 
        $out = array();         
        foreach ($links as $title => $attributes) {
			
			if($checkPermissions && !$this->Permissions->hasPermission($attributes['route'])) {
				continue;
			}
			
			$link = $attributes['route'];

			if(
				preg_match('@'.$attributes['match'].'@', $this->here)
				&&
					(!isset($attributes['ignoreMatch']) || !preg_match('@'.$attributes['ignoreMatch'].'@', $this->here))
			) 
            {
                $out[] = sprintf($this->tags['li'],' class="current nav_'.h(Inflector::slug(strtolower($title))).'"',$this->Html->link('<span>'.h($title).'</span>', $link, array('escape'=>false)));
			} 
            else 
            { 
                $out[] = sprintf($this->tags['li'],' class="nav_'.h(Inflector::slug(strtolower($title))).'"',$this->Html->link('<span>'.h($title).'</span>', $link, array('escape'=>false))); 
            } 
        } 
        $tmp = join("\n", $out); 
        return $this->output(sprintf($this->tags[$type],$this->_parseAttributes($htmlAttributes), $tmp)); 
    } 

/**
 * Allows an anchor to be declared for a page block. Populates a $viewVars['sections'], which can be used to generate a navigation element such as jump_menu.ctp.
 *
 * @param  string  $title The title text, such a "Details"
 * @param  string  $url A custom anchor. If none is provided, a slugified version of $title will be used instead.
 * @return string
 */    
    function anchor($title, $url = null) {
    	if(empty($url)) { 
    		$url = low(Inflector::slug($title)) . '_jump';
    	}

		// Put the details in a view variable so they can be output elsewhere.
    	$view =& ClassRegistry::getObject('view');
    	$view->viewVars['sections'][$title] = '#' . $url;

    	return '<a name="' . $url . '" class="jump_anchor"></a>';
    }
} 
?>
