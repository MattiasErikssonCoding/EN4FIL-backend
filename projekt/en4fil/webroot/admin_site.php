<?php 
/**
 * This is a en4fil pagecontroller.
 *
 */
// Include the essential config-file which also creates the $en4fil variable with its defaults.
include(__DIR__.'/config.php'); 

// Do it and store it all in variables in the en4fil container.
$en4fil['title'] = "My me-site";
$en4fil['main'] = <<<EOD
<p>Administration av hemsidan. </p>
<ul>
<li><a href=admin_games.php>Administrera 'Spel'</a></li>
<li><a href=view.php>Administrera Text</a></li>
<li><a href=source.php>Källkod</a></li>
<li><a href=logout.php>Logga ut</a></li>
</ul>

EOD;
// Finally, leave it all to the rendering phase of en4fil.
include(EN4FIL_THEME_PATH);
?>
