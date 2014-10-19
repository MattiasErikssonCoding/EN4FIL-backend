<?php 
/**
 * This is a en4fil pagecontroller.
 *
 */
// Include the essential config-file which also creates the $en4fil variable with its defaults.
include(__DIR__.'/config.php'); 

// Add style for csource
$en4fil['stylesheets'][] = 'css/source.css';


// Create the object to display sourcecode
$source = new CSource(array('secure_dir' => '..', 'base_dir' => '..'));

// Do it and store it all in variables in the en4fil container.
$en4fil['title'] = "Visa källkod";
$en4fil['main'] = "<h2>Visa Källkoden</h2>\n" . $source->View();
 
// Finally, leave it all to the rendering phase of en4fil.
include(EN4FIL_THEME_PATH);
?>
