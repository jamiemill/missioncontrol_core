<?php

$defaults  = array(
	'heading'=>null,
	'maxHeadingLength'=>35,
	'meta'=>true,
	'index'=>true,
	'add'=>true,
	'delete'=>true,
	'edit'=>true,
	'duplicate'=>false,
	'modelClass'=>$modelClass,
	'extras'=>array() // expects an array of extra links, each of which is like array('title'=>'Make Call','url'=>$urlInArrayOrStringFormat)
);

$settings = isset($recordHeaderSettings) ? am($defaults,$recordHeaderSettings) : $defaults;

$pluginName = empty($this->params['plugin']) ? false : $this->params['plugin'];
$pluralHumanModelName = Inflector::humanize(Inflector::underscore(Inflector::pluralize($settings['modelClass'])));
$singularlHumanControllerName = Inflector::singularize(Inflector::humanize($this->params['controller']));
if($pluginName) {
	$pluralHumanModelName = str_replace(Inflector::humanize(Inflector::underscore($pluginName)).' ', '', $pluralHumanModelName);
	$singularlHumanControllerName = str_replace(Inflector::humanize(Inflector::underscore($pluginName)).' ', '', $singularlHumanControllerName);
}

$heading = '';

if($settings['heading']) {
	$heading = $settings['heading'];
} elseif($this->params['action'] == 'index' || $this->params['action'] == 'admin_index') {
	$heading = __('All',true).' '.$pluralHumanModelName;
} elseif($this->params['action'] == 'add' || $this->params['action'] == 'admin_add') {
	$heading = __('Add',true).' '.Inflector::humanize($settings['modelClass']);
} elseif(isset($data[$modelClass][$modelDisplayField])) {
	$heading = $data[$modelClass][$modelDisplayField];
}
?>

<div class="record_navigation">
	
	<?php if(($this->params['action'] != 'index' && $this->params['action'] != 'admin_index') && $settings['index']) : ?>
			<?php echo $html->link('&lsaquo; '.__('All',true).' '.h($pluralHumanModelName),array('action'=>'index','controller'=>$this->params['controller']), array('class'=>'navbutton','escape'=>false)) ?>
	<?php endif ?>		
	
	<?php if(($this->params['action'] != 'add' && $this->params['action'] != 'admin_add') && $settings['add']) : ?>
		<?php echo $html->link('+ '.__('Add',true).' '.h($singularlHumanControllerName),array('action'=>'add','controller'=>$this->params['controller']), array('class'=>'navbutton','escape'=>false)) ?>
	<?php endif ?>
	
	<?php if(($this->params['action'] == 'view' || $this->params['action'] == 'admin_view') && $settings['delete']) : ?>
		<?php echo $html->link(__('Delete',true).' '.h($singularlHumanControllerName), array('action'=>'delete', $this->params['pass'][0]), array('class'=>'navbutton','escape'=>false,'confirm'=>__('Are you sure you want to delete this record?',true))); ?>
	<?php endif ?>
	
	<?php if(($this->params['action'] == 'view' || $this->params['action'] == 'admin_view') && $settings['edit']) : ?>
		<?php echo $html->link(__('Edit',true).' '.h($singularlHumanControllerName), array('action'=>'edit', $this->params['pass'][0]), array('class'=>'navbutton','escape'=>false)); ?>
	<?php endif ?>
	
	<?php foreach($settings['extras'] as $extra) : ?>
		<?php $icon = isset($extra['icon']) ? $html->image($extra['icon']) : '';  ?>
		<?php echo $html->link($icon.' '.$extra['title'], $extra['url'], array('class'=>'navbutton','escape'=>false)); ?>
	<?php endforeach ?>

</div>

<?php if(($this->params['action'] == 'view' || $this->params['action'] == 'admin_view') && $settings['meta']) : ?>
	<?php //echo $this->element('meta',array('model'=>$settings['modelClass'])) ?>
<?php endif ?>
