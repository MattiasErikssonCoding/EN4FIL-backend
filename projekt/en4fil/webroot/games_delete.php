<?php 
include(__DIR__.'/config.php');

/**
 * Connect to a MySQL database using PHP PDO
 */
$db = new CDatabase($en4fil['database']);
$content = new CGames($db);

isset($_SESSION['user']) or die('Check: You must login to delete.');
$id = isset($_GET['id']) ? $_GET['id'] : null;
$pageId = "delete";
$output = null;

if(isset($_GET['y'])){
  	$output = $content->deleteGame($id);
}

/*
 * Execute and store in variables in the en4fil container.
 */
$en4fil['title'] = "Delete";

$en4fil['main'] = <<<EOD
<h1>Ta bort data</h1>
<article id="restore">
	<h1>Ta bort informationen i detta inlägg?</h1>
    	<p>Är du helt säker på att du vill ta bort den här informationen?</p>
    	<p><a href="games_delete.php?y&id={$id}">Ja</a> <a href="admin_games.php">Nej </a></p>
    	{$output}
</article>

<p><a href="admin_games.php">Tillbaka till innehåll</a></p>

EOD;

/**
 * Hand over to the rendering phase of en4fil
 */
include(EN4FIL_THEME_PATH);  
