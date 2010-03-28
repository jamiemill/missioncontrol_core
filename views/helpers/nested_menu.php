<?php  

/**
 *
 * Jamie's menu helper
 * based on Andy Dawson't tree helper, and adds ability to differentiate selected item from others.
 * But only handles findAllThreaded() resultsets, not TreeBehaviour's children()
 */


class NestedMenuHelper extends AppHelper { 

    var $helpers = array ('Html'); 
	var $flatten_result = array(); // holds the results of a flattening operation
	var $return; // output
	
	var $myview; // will hold a reference to the view object, so that we can call renderelement
	
	
	/**
	 * 	
	 * 	Generates nested unordered lists, with support for marking currently selected items
	 * 	
	 * 	$data = nested array of page results
	 * 	
	 * 	$settings = array with these keys:
	 * 
	 * 	currentPath = array of IDs of the currently active item and all its parents. 
	 * 			each item when rendered is checked to see if it is in this array, and if so, 
	 * 			gets 'class="selected" added to its <li> tag
	 * 
	 * 	initialDepth = how many levels should be expanded past the root. true means infinite, 
	 * 			integer means number of levels to show. (currentPath items and their siblings 
	 * 			are always shown - this is how to produce "open" branches.)
	 * 	
	 * 	depthLimit = not implemented yet - should stop us from opening the tree out too far - 
	 * 			so that sub pages don't necessarily show up even if they are currently being viewed
	 * 	
	 * 	baseListId = ID of the root UL
	 * 	
	 * 	baseListClass = class to apply to the root UL
	 * 	
	 * 	TODO: allow custom HTML code for each item - so that edit/delete links could be output too. float:right with css?
	 * 	
	 * 	TODO: add a check for show_in_menu as treebehaviour->children() doesn't support find conditions
	 * 	
	 * 	
	 */
	
	function generate ($data,$settings = array()) {
		
		$this->myview = ClassRegistry::getObject('view');

		$this->return = '';
		$this->__generate($data,$settings);
		return  $this->return;
	}
	
	/* recursive function for generate() */
	
	function __generate ($data,$settings = array()) {
		
		App::import('Helper','Html');
	    $html = new HtmlHelper();
		
		// defaults that get overwritten by settings
		$initialDepth = true;
		$indent = -1;
		$currentPath = array();
		$baseListId = '';
		$baseListClass = '';
		$li_class_prefix = '';
		$depthLimit = 999;
		$currentDepth = -1;
		$parentsAreLinks = true;


		extract($settings);
		
		$indent++;
		$currentDepth++;

		
		// these are the settings we want to carry forward into the nxt child
		$settings['initialDepth'] = $initialDepth;
		$settings['indent'] = $indent;
		$settings['currentDepth'] = $currentDepth;
		$settings['baseListId'] = '';
		$settings['baseListClass'] = '';
		$settings['depthLimit'] = $depthLimit;
		$settings['parentsAreLinks'] = $parentsAreLinks;
		
		$this->return .= '<ul id="'.$baseListId.'" class="'.$baseListClass.'">'."\n";
		
		$tot = count($data);
		$num = 1;
		
		foreach($data as $item) {
			
			$selected = '';
			$firstLast = '';
			if($num == 1) {
				$firstLast .= ' first';
			}
			if($num == $tot) {
				$firstLast .= ' last';
			}
			
			if(in_array($item['CorePage']['id'],$currentPath)) {
				$selected = ' selected in_current_path'; // 'selected' is there only for backward-compatibility, should be removed in future
			}
			if(count($currentPath) && $item['CorePage']['id'] == $currentPath[count($currentPath)-1]) {
				$selected = ' current';
			}
			
			$depthClass = ' depth_'.$currentDepth;
				
			if(!$parentsAreLinks && !empty($item['children'])) {
				$this->return .= str_repeat("\t",$indent)
					.'<li class="'.$li_class_prefix.$item['CorePage']['id'].$depthClass.$selected.$firstLast.'">'
					.$item[$settings['model']][$settings['display']];
				
			}
			else {
				$this->return .= str_repeat("\t",$indent)
					.'<li class="'.$li_class_prefix.$item['CorePage']['id'].$depthClass.$selected.$firstLast.'">'
					.$html->link($item[$settings['model']][$settings['display']],'/pages/'.$item['CorePage']['slug']);
			}
				
			

			

			if (!empty($item['children']) && $currentDepth < $depthLimit  && (
								$initialDepth === true  
								|| $initialDepth > 0 
								|| in_array($item['CorePage']['id'],$currentPath)
								)) {
				
				if($initialDepth !== true) {
					$settings['initialDepth']--;
				}

				if($currentDepth <= $depthLimit) {
					$this->__generate($item['children'],$settings);
				}
			}
			
			// if this item has a renderelement
			// if(!empty($item['CorePage']['append_child_element'])) {
			// 
			// 				$this->return .= $this->myview->element($item['CorePage']['append_child_element']);
			// 			}
			
			$this->return .= "</li>\n";
			$num++;
		}
		
		$this->return .= "</ul>\n";

	}
	
	
	/*
	
	flattens results of findAllThreaded into one-dimensional array with new key "treeDepth" which tells us the original depth the item existed at. Use treeDepth to construct indents
	
	e.g.
	
	$items = $nestedMenu->flatten($menu);
	foreach($items as $item) {
		echo str_repeat("\t",$item['treeDepth']).$item['CorePage']['title']."\n";
		... [echo edit / delete etc] ...
	}
	
	*/
	
	
	function flatten($tree) {
		$this->flatten_result = array();
		$this->__flatten($tree);
		return $this->flatten_result;
	}
	
	/* recursive method for flatten() */
	
	function __flatten($tree,$depth = 0) {
		foreach ($tree as $item) {
			$item['treeDepth'] = $depth;
			$item['hasChildren'] = !empty($item['children']);
			$tmp_children = $item['children'];
			$item['children'] = array();
			$this->flatten_result[] = $item;
			
			if (!empty($tmp_children)) {
				$this->__flatten($tmp_children,$depth+1); // recurse
			}
			
		}
	}
 
} 
?>