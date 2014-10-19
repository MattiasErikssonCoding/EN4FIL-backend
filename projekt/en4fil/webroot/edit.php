<?php
include(__DIR__.'/config.php');

/**
 * Connect to a MySQL database using PHP PDO
 */
$db = new CDatabase($en4fil['database']);
$content = new CContent($db);

/*
 * Get essential parameters
 */ 
$id     = isset($_POST['id'])    ? strip_tags($_POST['id']) : (isset($_GET['id']) ? strip_tags($_GET['id']) : null);
$title  = isset($_POST['title']) ? $_POST['title'] : null;
$slug   = isset($_POST['slug'])  ? $_POST['slug']  : null;
$url   = isset($_POST['url'])  ? strip_tags($_POST['url']) : null;
$data   = isset($_POST['DATA'])  ? $_POST['DATA']  : array();
$type   = isset($_POST['TYPE'])  ? strip_tags($_POST['TYPE']) : array();
$filter = isset($_POST['FILTER']) ? $_POST['FILTER'] : array();
$published = !empty($_POST['published'])  ? strip_tags($_POST['published']) : array();
$save	= isset($_POST['save']) ? true : false;
$acronym = isset($_SESSION['user']) ? $_SESSION['user']->acronym : null;

/*
 * Check parameter validity
 */ 
isset($acronym) or die('Check: You must login to edit.');
is_numeric($id) or die('Check: Id must be numeric.');

$output = null;
if($save) {
	$output = $content->edit();
}

$c = $content->selectWithId($id);

/*
 * Sanitize content to prevent SQL injections
 */
$title  	= htmlentities($c->title, null, 'UTF-8');
$slug   	= htmlentities($c->slug, null, 'UTF-8');
$url    	= $c->url;
$data   	= htmlentities($c->DATA, null, 'UTF-8');
$type   	= $c->TYPE;
$filter 	= htmlentities($c->FILTER, null, 'UTF-8');
$published 	= $c->published;

/*
 * Execute and store in variables in the en4fil container.
 */
$en4fil['title'] = "Edit information";
$en4fil['debug'] = $db->Dump();

$en4fil['main'] = <<<EOD
<h1> Editera nyheter </h1>

<form method=post>
	<fieldset>
  		<legend>Uppdatera innehåll i nyheterna</legend>
  		<input type='hidden' name='id' value='{$id}'/>
  		<p><label>Titel:<br/><input type='text' name='title' value='{$title}'/></label></p>
  		<p><label>Slug:<br/><input type='text' name='slug' value='{$slug}'/></label></p>
  		<p><label>Url:<br/><input type='text' name='url' value='{$url}'/></label></p>
  		<p><label>Text:<br/><textarea rows="10" cols="80" name='DATA'>{$data}</textarea></label></p>
  		<p><label>Type:<br/>Du kan skapa 'post' eller 'page'.<br/><input type='text' name='TYPE' value='{$type}'/></label></p>
  		<p><label>Filter:<br/>Du kan använda 'nl2br','bbcode','markdown' och/eller 'link' eller lämna tomt.<br/><input type='text' name='FILTER' value='{$filter}'/></label></p>
  		<p><label>Publiceringsdatum:<br/>Om du vill spara som utkast, sätt publiceringsdatum tomt.<br/><input type='text' name='published' value='{$published}'/></label></p>
  		<p class=buttons><input type='submit' name='save' value='Spara'/> <input type='reset' value='Återställ'/></p>
  		<p><a href='view.php'>Tillbaka till översikt</a></p>
  		<output>
			{$output}
		</output>
	</fieldset>
</form>

EOD;

/**
 * Hand over to the rendering phase of en4fil
 */
include(EN4FIL_THEME_PATH); 
