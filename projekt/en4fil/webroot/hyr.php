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

<h1> Butiken är för tillfället inte aktiv då detta inte är ett riktigt företag. </h1>
<p> För mer information, kontakta mig på g10mater@student.his.se </p>

EOD;
// Finally, leave it all to the rendering phase of en4fil.
include(EN4FIL_THEME_PATH);
?>
