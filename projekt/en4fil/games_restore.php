<?php
include(__DIR__.'/config.php');

/**
 * Connect to a MySQL database using PHP PDO
 */
$db = new CDatabase($en4fil['database']);
$games = new CGames($db);

$output = null;
if(isset($_POST['restore']) || isset($_GET['restore'])) {
	$output = $games->RestoreGames();
}

/**
 * Execute and store in variables in the Raket container.
 */
$en4fil['title'] = "Återställ information";

$en4fil['main'] = <<<EOD
<h1>Återställ spel</h1>
<form method=post>
	<input type=submit name=restore value='Återställ'/>
	<output>{$output}</output>
</form>
EOD;

/**
 * Hand over to the rendering phase of en4fil
 */
include(EN4FIL_THEME_PATH); 
