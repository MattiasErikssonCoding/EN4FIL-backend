<?php
include(__DIR__.'/config.php');

/**
 * Connect to a MySQL database using PHP PDO
 */
$db = new CDatabase($en4fil['database']);
$game = new CGames($db);

/*
 * Get essential parameters
 */ 
$id     = isset($_POST['id'])    ? strip_tags($_POST['id']) : (isset($_GET['id']) ? strip_tags($_GET['id']) : null);
$title  = isset($_POST['title']) ? $_POST['title'] : null;
$image  = isset($_POST['image']) ? $_POST['image'] : null;
$console  = isset($_POST['console']) ? $_POST['console'] : null;
$price  = isset($_POST['price']) ? $_POST['price'] : null;
$year  = isset($_POST['YEAR']) ? $_POST['YEAR'] : null;
$data   = isset($_POST['DATA'])  ? $_POST['DATA']  : array();
$save	= isset($_POST['save']) ? true : false;
$acronym = isset($_SESSION['user']) ? $_SESSION['user']->acronym : null;

/*
 * Check parameter validity
 */ 
isset($acronym) or die('Check: You must login to edit.');
is_numeric($id) or die('Check: Id must be numeric.');

$output = null;
if($save) {
	$output=$game->EditGame();
}

$c = $game->selectGameWithId($id);

/*
 * Sanitize content to prevent SQL injections
 */
$title  	= htmlentities($c->title, null, 'UTF-8');
$image  	= htmlentities($c->image, null, 'UTF-8');
$price  	= htmlentities($c->price, null, 'UTF-8');
$data	  	= htmlentities($c->DATA, null, 'UTF-8');
$console  	= htmlentities($c->console, null, 'UTF-8');
$year   	= htmlentities($c->YEAR, null, 'UTF-8');

/*
 * Execute and store in variables in the en4fil container.
 */
$en4fil['title'] = "Edit information";
$en4fil['debug'] = $db->Dump();

$en4fil['main'] = <<<EOD
<h1> Editera data </h1>

<form method=post>
	<fieldset>
  		<legend>Uppdatera innehåll</legend>
  		<input type='hidden' name='id' value='{$id}'/>
  		<p><label>Titel:<br/><input type='text' name='title' value='{$title}'/></label></p>
		<p><label>Synopsis:<br/><textarea rows="10" cols="80" name='DATA'>{$data}</textarea></label></p>
  		<p><label>Bildnamn:<br/><input type='text' name='image' value='{$image}'/></label></p>
  		<p><label>Pris:<br/><input type='text' name='price' value='{$price}'/></label></p>
  		<p><label>Konsol:<br/><input type='text' name='console' value='{$console}'/></label></p>
  		<p><label>År:<br/><input type='text' name='YEAR' value='{$year}'/></label></p>
  		<p class=buttons><input type='submit' name='save' value='Spara'/> <input type='reset' value='Återställ'/></p>
  		<p><a href='admin_games.php'><button type="button">Tillbaka</button></a></p>
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
