<?php
include(__DIR__.'/config.php');

/**
 * Connect to a MySQL database using PHP PDO
 */
$db = new CDatabase($en4fil['database']);
$content = new CContent($db);

if(isset($_POST['create'])) {
    $title = $_POST['title'];
    $content->CreateContent($title);
}

$en4fil['debug'] = $db->Dump();

$en4fil['main'] = <<<EOD
<h1>Skapa information</h1>
<form method=post>
	<fieldset>
		<legend><b>Skapa nyhet</b></legend>
		<p><lable>Titel:<br/><input type='text' name='title'/></lable></p>
		<p><input type='submit' name='create' value='Spara'/></p>
	</fieldset>
</form>
EOD;

/**
 * Hand over to the rendering phase of en4fil
 */
include(EN4FIL_THEME_PATH); 
