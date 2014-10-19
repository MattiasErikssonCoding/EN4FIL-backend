<?php

include(__DIR__.'/config.php');
 
/**
 * Set up database connection.
 *
 */ 
$db = new CDatabase($en4fil['database']);
$user = new CUser($db);

$success=true;

if(!$user->IsAuthenticated()){ 
  	if(isset($_POST['acronym'], $_POST['password'])){ 
    		$success=$user->login($_POST['acronym'], $_POST['password']); 
  	} 
}

if(!$success) { 
  	$output = "Inloggningen misslyckades, verifiera användarnamn och lösenord."; 
}else { 
  	$output=$user->AuthenticateOutput();
}  

$acronym = isset($_SESSION['user']) ? $_SESSION['user']->acronym : null;
$output .= $acronym ? "<a href=admin_site.php>Administrera hemsida</a>" : null; 


$en4fil['title'] = "Login";

$en4fil['main'] = <<<EOD
<h1>Login</h1>

<form method=post>
	<fieldset>
  		<p><label>Användare:</br><input type='text' name='acronym' value=''/></label></p>
  		<p><label>Lösenord:</br><input type='text' name='password' value=''/></label></p>
  		<p><input type='submit' name='login' value='Login'/></p>
  		<output><b>{$output}</b></output>
  	</fieldset>
</form>

EOD;
 
/**
 * Finally, leave it to the rendering phase of En4fil.
 *
 */
include(EN4FIL_THEME_PATH);

