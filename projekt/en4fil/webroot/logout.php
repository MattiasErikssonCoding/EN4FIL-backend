<?php  

include(__DIR__.'/config.php'); 
 
/**
 * Set up database connection.
 *
 */ 
$db = new CUser($en4fil['database']);

if(isset($_POST['btnLogout'])){ 
  	$user->logout(); 
} 
$output=$db->AuthenticateOutput();

// Get incoming parameters
$acronym = isset($_SESSION['user']) ? $_SESSION['user']->acronym : null;

if($acronym) {
  	$output = "Du är inloggad som: {$_SESSION['user']->name}";
}else {
  	$output = "Du har loggat ut.";
}

// Logout the user
if(isset($_POST['logout'])) {
  	unset($_SESSION['user']);
  	header('Location: logout.php');
}

$en4fil['title'] = "Logout";

$en4fil['main'] = <<<EOD
<h1>Log out</h1>
<form method=post>
  	<fieldset>
  		<p>Är du säker på att du vill logga ut?</p> 
  		<p><input type='submit' name='logout' value='Logout'/></p>
  		<output><b>{$output}</b></output>
  	</fieldset>
</form>
EOD;
 
/**
 * Finally, leave it to the rendering phase of en4fil.
 *
 */
include(EN4FIL_THEME_PATH);
