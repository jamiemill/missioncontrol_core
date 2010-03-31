<?php
class LayoutHelper extends AppHelper {

	var $helpers = array('Html', 'Time', 'Form');

/**
 * Returns a summary sentence for the results being listed on a page, e.g. "Listing four articles".
 * @param int $total The number of results
 * @param array $options Output options
 */
	function summary($count, $options = array()) {
		// If a thing hasn't been specified, use the name of the current controller. The thing for http://example.com/articles would become 'Article'.
		if (!isset($options['thing'])) {
			$options['thing'] = low(Inflector::singularize(Inflector::humanize($this->params['controller'])));
		}
		
		// If a total has been specified, the thing should be pluralised according to that. For example, 'Listing 1 of 20 photos'.
		// If no total is specified, the thing should be pluralised according to the count. For example, 'Listing 20 photos'.
		if(isset($options['total'])) {
			$number = $options['total'];
			$of = 'of ' . $this->Word->fromNumber($options['total']) . ' '; 
		} else {
			$number = $count;
			$of = null;
		}
		
		// If the number isn't 1, apply the specified plural. Use 's' as a default.
		if ($number <> 1) {
			if (isset($options['plural'])) {
				$options['thing'] = $options['plural'];
			} else {
				$options['thing'] = $options['thing'] . 's';
			}
		}
		
		// If an array of matched criteria was included, convert it to a list in natural language.
		if(isset($options['criteria'])) {
			$options['criteria'] = ' ' . $this->Text->toList($options['criteria']);
		}

		if(!isset($options['after'])) {
			$options['after'] = '&hellip;';
		}
		
		if(!isset($options['showTotal'])) {
			$options['showTotal'] = true;
		}
		
		if($count > 0 && $options['showTotal'] !== false) {
			// Apply a default action word of 'listing' to start the sentence with.
			if(!isset($options['before'])) {
				$options['before'] = 'Listing ';
			}
			$out = $options['before'] . $this->Word->fromNumber($count) . ' ' . $of . $options['thing'] . @$options['criteria'] . $options['after'];
			$class = 'summary';
		} else if ($number == 0) {
			// Make a default 'no results' message if one hasn't been specified.
			if(!isset($options['errorMessage'])) {
				$options['errorMessage'] = 'There are no ' . $options['thing'] . ' to show for that request.';
			}			
			$out = $options['errorMessage'];
			$class = 'summary error';
		} else {
			$out = null;
			$class = null;
		}
		
		// Nest the summary in a paragraph where suitable. 
		if(!isset($options['para'])) {
			$options['para'] = true;
		}
		if($options['para'] == true) {
			$out = $this->Html->para($class, $out);
		}

		return $out;
	}

/**
 * Returns an image. 
 *
 * @param  string  $id The image ID.
 * @param  string  $filename The filename of the image.
 * @param  array   $options Extra options. 
 * @return string
 */
	function image($id, $filename, $options = array()) {
		// Set default options.
		if(isset($options['caption']) && (empty($options['htmlAttributes']['title']))) {
			$options['htmlAttributes']['title'] = strip_tags($options['caption']);
		}
		if(isset($options['caption']) && empty($options['htmlAttributes']['alt'])) {
			$options['htmlAttributes']['alt'] = $options['htmlAttributes']['title'];
		}
			
		if(empty($options['htmlAttributes'])) {
			$options['htmlAttributes'] = null;
		}
		if(empty($options['dir'])) {
			$options['dir'] = 'project_pages';
		}
		if(!isset($options['div'])) {
			$options['div'] = true;
		}
		if(empty($options['class'])) {
			$options['class'] = 'image';
		}
		if(!isset($options['placeholder'])) {
			$options['placeholder'] = false;
		}	
		if(isset($options['refresh']) && $options['refresh'] !== false) {
			$rand = '?' . time();
		} else {
			$rand = null;
		}

		// Make the img tag.
		if(file_exists(WWW_ROOT . 'img' . DS . $options['dir'] . DS . $id . DS . $filename)) {			
			$out = $this->Html->image($options['dir'] . DS .  $id . DS . $filename . $rand, $options['htmlAttributes']);
		} else if($options['placeholder'] == true) {
			$out =  $this->Html->image($options['dir'] . DS . 'no_image.png' . $rand, $options['htmlAttributes']);
		} else {
			return null;
		}

		if(isset($options['caption'])) {
			$out .= '<br /><span>' . $options['caption'] . '</span>';
		}
		if(!empty($options['subcaption'])) {
			$out .= '<br /><span>' . strip_tags($options['subcaption']) . '</span>';
		}		
		if(isset($options['url'])) {
			$out = $this->Html->link($out, $options['url'], false, false, false);
		}
		
		if(isset($options['after'])) {
			$out .= $options['after'];
		}
		if($options['div'] == true) {
			$out = '<div class="' . $options['class'] . '">' . $out . '</div>';
		}
		return $out;
	}
	
/**
 * Returns a metadata sentence, such as 'posted by graemegarden 2 weeks ago'.
 *
 * @param  array  $author Details of the entry's author.
 * @param  string $time A timestamp of when the entry was created. 
 * @param  array   $options Extra options. 
 * @return string
 */
	function meta($author, $time = null, $options = array()) {
		if(empty($author)) {
			return false;
		}
		
		if(!isset($options['before'])) {
			$options['before'] = 'Added by ';
		}
		if(!isset($options['after'])) {
			$options['after'] = '.';
		}
		if(!isset($options['image'])) {
			$options['image'] = false;
		}
		
		$url = array('controller' => 'users', 'action' => 'view', $author['username']);
		
		if($options['image'] == true) {
			$image = $this->Html->link($this->image($author['id'], array('dir' => 'users', 'div' => false, 'size' => 50)), $url, false, false, false);
		} else {
			$image = null;
		}
		
		$author = $this->Html->link($this->UserInfo->name($author), $url);
		if(!is_null($time)) {
			$time = ' ' . $this->Time->relativeTime($time);
		}
		
		$out = '<div class="meta">' . $this->Html->para(null, $options['before'] . $author . $time . $options['after']) . $image . '</div>';
		return $out;
	}
	
/**
 * Converts a boolean state (i.e. 1 or 0) into something more readable (e.g. 'yes' and 'no').
 *
 * @param  array   $params Output options. 
 * @return string 'View' link
 */
	function flag($value = null, $grey = false) {
		$suffix = $grey ? '_grey' : '';
		switch($value) {
			case 1:
				$out = $this->Html->image('/core/img/admin/tick'.$suffix.'.png', array('alt' => 'Yes', 'title' => 'Yes', 'class' => 'flag yes'));
				break;
			case 0: 
				$out = $this->Html->image('/core/img/admin/cross'.$suffix.'.png', array('alt' => 'No', 'title' => 'No', 'class' => 'flag no'));
				break;
			default:
				$out = $this->Html->image('/core/img/admin/unknown'.$suffix.'.png', array('alt' => 'Unknown', 'title' => 'Unknown', 'class' => 'flag unknown'));
				break;
		}
		return $out;
	}
	
	function greyFlag($value) {
		return $this->flag($value,true);
	}
	
/**
 * Returns a heading populated with appropriate text. 
 *
 * @param  string  $text Button text.
 * @param  string  $url The location to link to.
 * @param  array   $options Extra options. 
 * @return string
 */
	function heading($text = null, $options = array()) {
		// Use some defaults.
		if(empty($options['thing'])) {
			$options['thing'] = Inflector::humanize(Inflector::singularize($this->params['controller']));
		}
		if(empty($options['plural'])) {
			$options['plural'] = Inflector::humanize($this->params['controller']);
		}
		if(empty($options['level'])) {
			$options['level'] = 2;
		}
		if(empty($text)) {
			switch($this->params['action']) {
				case 'add':
				case 'admin_add':
					$text = __('Add',true).' ' . $options['thing'];
					break;
				case 'edit':
				case 'admin_edit':
					$text = __('Edit',true).' ' . $options['thing'];
					break;
				case 'view':
				case 'admin_view':
					$text = $options['thing'];
					break;
				case 'history':
				case 'admin_history':
					$text = $options['thing'] . ' '.__('History',true);
					break;
				default:
					$text = $options['plural'];
			}
		}
		if(!isset($options['global'])) {
			$options['global'] = true;
		}
		
		$out = '<h' . $options['level'] . '>' . $text . '</h' . $options['level'] . '>' . "\n";
		return $out;
	}

/**
 * Returns a help tip. Useful when used next to form fields. 
 *
 * @param  string  $text Tip text.
 * @param  array   $options Extra options. 
 * @return string
 */	
	function help($text, $options = array()) {
		if(empty($options['image'])) {
			$options['image'] = '/core/img/admin/help_img.png';
		}
		$out = $this->Html->image($options['image'], array('title' => 'Note: ' . $text, 'alt' => 'Help', 'class' => 'tips'));
		return $out;
	}

/**
 * Returns a content block. 
 *
 * @param  string  $text Tip text.
 * @param  array   $options Extra options. 
 * @return string
 */		
	function block() {
	
	}
	

	function getPageData($slug) {
		return $this->requestAction('/core/core_pages/request_page_data/'.$slug);
	}
	
	function getContentAreaBlocks($data, $slug) {
		$blocks = $data['CoreBlock'];
		$items = array();
		foreach($blocks as $block) {
			if($block['content_area'] == $slug) {
				$items[] = $block;
			}
		}
		return $items;
	}
		
	/**
	* Offers an alternative to the TimeHelper niceShort mothod which includes a time that's nt always desired.
	* Also returns empty strings if the date is 0 rather than the unix epoch which doesn't make sense in most situations.
	*/

	function niceShortNoTime($dateString = null, $userOffset = null,$highlightPast = false) {
		if(!$dateString || $dateString == '0000-00-00' || $dateString == '0000-00-00 00:00:00') {
			return '';
		}
		
		$date = $this->Time->fromString($dateString, $userOffset);
		
		$y = $this->Time->isThisYear($date) ? '' : ' Y';

		if ($this->Time->isToday($date)) {
			$ret = __('Today',true);
		} elseif ($this->Time->isTomorrow($date)) {
			$ret = __('Tomorrow',true);
		} elseif ($this->Time->wasYesterday($date)) {
			$ret = __('Yesterday',true);
		} elseif ($this->Time->isThisWeek($date)) {
			$ret = date("l", $date);
		} else {
			$ret = date("D jS M{$y}", $date);
		}
		if($highlightPast && $this->Time->fromString($dateString) < time()) {
			return $this->output('<span class="date_overdue">'.$ret.'</span>');
		}
		return $this->output($ret);
	}
	
	function formatOrEmpty($format,$dateString) {
		if(!$dateString || $dateString == '0000-00-00' || $dateString == '0000-00-00 00:00:00') {
			return '';
		}
		return $this->Time->format($format,$dateString);
	}
	
	function niceOrEmpty($dateString) {
		if(!$dateString || $dateString == '0000-00-00' || $dateString == '0000-00-00 00:00:00') {
			return '';
		}
		return $this->Time->nice($dateString);
	}
	
	/**
	* Returns a suggested string of classnames appropriate for the current request.
	* Contains the current plugin, controller, action and first parameter of
	* the current request. This is intended to be used as the HTML body tag's class value so that
	* specific styles can be set per-plugin, per-controller, per-action, or per-parameter
	* 
	* @return string a string of several appropriate classnames, for example: "plugin_core controller_core_pages action_view param0_donate"
	*/
	
	function bodyClass() {
		$out = implode(' ',
			array(
				'plugin_'.$this->params['plugin'],
				'controller_'.$this->params['controller'],
				'action_'.$this->params['action']
			)
		);
		if(!empty($this->params['pass'])) {
			$out .= ' param0_'.$this->params['pass'][0];
		}
		return $this->output($out);
	}
	
	
	function generateViewTable($data,$fields) {
		$out = '<table class="admin_listing admin_view">';
		foreach($fields as $field => $settings) {
			
			$defaults = array(
				'type'=>'string',
				'label'=>null,
				'value'=>null,
				'booleanType'=>'grey', // can also be 'colour' or 'text'
			);
			
			if(is_numeric($field)) {
				$field = $settings;
				$settings = $defaults;
			} else {
				$settings = am($defaults,$settings);
			}
			if(strpos($field,'.') !== false) {
				list($modelname,$fieldname) = explode('.',$field);
			} else {
				$modelname = array_shift(array_keys($data));
				$fieldname = $field;
			}
			if($settings['label'] === null) {
				$label = Inflector::humanize($fieldname);
			} else {
				$label = $settings['label'];
			}
			if($settings['value'] !== null) {
				$value = $settings['value'];
			} else {
				if(is_numeric(array_shift(array_keys($data[$modelname])))) {
					// we're probably dealing with HABTM
					$value = join(', ',Set::extract($data,'/'.$modelname.'/'.$fieldname));	
				} else {
					$value = $data[$modelname][$fieldname];				
				}
			}
			
			if($settings['type'] == 'boolean') {
				if($settings['booleanType'] == 'colour') {
					$value = $this->flag($value);		
				} elseif($settings['booleanType'] == 'grey') {
					$value = $this->greyFlag($value);
				} else {
					$value = $value ? __('yes',true) : __('no',true);	
				}
			} elseif ($settings['type'] == 'datetime') {
				$value =  $this->Time->nice($value);
			} elseif ($settings['type'] == 'date') {
				$value =  $this->Time->format('D, jS M Y', $this->Time->fromString($value));
			}
			
			$out .= sprintf('<tr><td>%s</td><td>%s</td></tr>', __($label,true), $value);
		}
		$out .= '</table>';
		return $this->output($out);
	}
	
	function html2text($data) {
		$replacements = array(
			'|\n+|' =>' ',
			'|\r+|'=>' ',
			'|<hr[^>]+>|i' => "<br /><br />--------------<br /><br />",
			'|[\t]+|'=>' ',
			'|[ ]{2,}|'=>' ',
			'|<p[^>]*>|i' =>"",
			'|</p>|i' =>"\n\n",
			'|<br[^>]+>|i'=>"\n",
			'|\n[ ]+|' => "\n",
			'|<style[^>]*>[^<]*</style>|i' => '',
			'|<script[^>]*>[^<]*</script>|i' => ''
		);
		$data = preg_replace(array_keys($replacements), array_values($replacements), $data);
		$data = strip_tags($data);
		$data = html_entity_decode($data,ENT_QUOTES,'UTF-8');
		return $data;
	}
	
	function timeInputNoSeparator($fieldName, $options) {
		$defaults = array('label'=>null);
		$settings = am($defaults,$options);
		$out = '<div class="input time">';
		$out .= $this->Form->label($fieldName,$settings['label']);
		$out .= $this->timeNoSeparator($fieldName);
		$out .= $this->Form->error($fieldName);
		$out .= '</div>';
		return $this->output($out);
	}
	
	function timeNoSeparator($fieldName) {
		return $this->Form->hour($fieldName, true, null, array(), true) .' '.$this->Form->minute($fieldName, null, array(), true);
	}
	
	function getCustomField($data,$fieldName) {
		$value = Set::extract('/CoreCustomField[name='.$fieldName.']/value',$data);
		if(!empty($value)) {
			return array_shift($value);
		} else {
			return null;
		}
	}
	
}
?>
