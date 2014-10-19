<?php
/**
 * Config-file for en4fil. Change settings here to affect installation.
 *
 */
 
/**
 * Set the error reporting.
 *
 */
error_reporting(-1);              // Report all type of errors
ini_set('display_errors', 1);     // Display all errors 
ini_set('output_buffering', 0);   // Do not buffer outputs, write directly

 
/**
 * Define en4fil paths.
 *
 */
define('EN4FIL_INSTALL_PATH', __DIR__ . '/..');
define('EN4FIL_THEME_PATH', EN4FIL_INSTALL_PATH . '/theme/render.php');
 
/**
 * Include bootstrapping functions.
 *
 */
include(EN4FIL_INSTALL_PATH . '/src/bootstrap.php');
 
/**
 * Start the session.
 *
 */
session_name(preg_replace('/[^a-z\d]/i', '', __DIR__));
session_start();
 
/**
 * Create the en4fil variable.
 *
 */
$en4fil = array();
 
/**
 * Site wide settings.
 *
 */
$en4fil['lang']         = 'sv';
$en4fil['title_append'] = ' | en4fil webbtemplate';

/**
 * Theme related settings.
 *
 */
$en4fil['stylesheets'] = array('css/style.css');

$en4fil['favicon']    = 'favicon.ico';

$en4fil['menu'] = array(
	'class' => 'navbar',
  	'items' => array(
    	'start'   => array('text'=>'Start',        'url'=>'index.php',     'title' => 'startsidan'),
    	'nyheter'  => array('text'=>'Nyheter',       'url'=>'nyheter.php',    'title' => 'blog'),
    	'database'  => array('text'=>'Spel',       'url'=>'database.php',    'title' => 'database'),
    	'omoss'  => array('text'=>'Om oss',       'url'=>'page.php?url=om_oss',    'title' => 'Om oss'),
  	),
  	'callback_selected' => function($url) {
    		if(basename($_SERVER['SCRIPT_FILENAME']) == $url) {
      			return true;
    		}
  	}
);


/**
 * Define recurring page content.
 *
 */
$en4fil['header'] = <<<EOD
<span class='mainimg'><img src="img.php?src=rental_banner.png&save-as=jpg&quality=100&width=800&sharpen" alt="RetroGameRental banner" /></span>
EOD;

$en4fil['footer'] = <<<EOD
<footer><span class='sitefooter'>Copyright (c) RetroGameRental - <a href="login.php">Administrera</a></span></footer>
EOD;

/*
 *
 *Database information, login etc.
 *
 */
$en4fil['database']['dsn'] 		= 'mysql:host=localhost;dbname=dbname;';
$en4fil['database']['username'] 	= 'uname';
$en4fil['database']['password'] 	= 'pword';
$en4fil['database']['driver_options'] 	= array(PDO:: MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8'");
