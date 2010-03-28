<?php App::import('helper','html');

$html = new HtmlHelper;

?>

<?php if(!isset($confirm)) {
	$confirm = false;
}?>

<?php echo $html->link('<span>'.$label.'</span>',$link,array('class'=>'button','escape'=>false.'confirm'=>$confirm)) ?>