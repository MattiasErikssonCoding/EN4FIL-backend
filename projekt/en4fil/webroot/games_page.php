<?php 
include(__DIR__.'/config.php'); 

$en4fil['title'] = "Synopsis Pages";

/**
 * Connect to a MySQL database using PHP PDO
 */ 
$db = new CDatabase($en4fil['database']);
$gamespage = new CGamesPage($db);
$filtertext = new CTextFilter();

$res = $gamespage->getGame();

$acronym = isset($_SESSION['user']) ? $_SESSION['user']->acronym : null;
$editLink = $acronym ? "<a href='edit.php?id={$res->id}'>Uppdatera</a>" : null;

$title  = htmlentities($res->title, null, 'UTF-8');
$data   = $filtertext->doFilter(htmlentities($res->DATA, null, 'UTF-8'), 'nl2br');
$image	= $res->image;
/*
 * Execute and store in variables in the en4fil container.
 */ 

$en4fil['title'] = $title;
$enfil['debug'] = $db->Dump();

$en4fil['main'] = <<<EOD
<article class="readable">
<h1>{$en4fil['title']}</h1>
<div class="right">
<img src='img.php?src={$image}&save-as=jpg&quality=100&height=250&sharpen' alt='{$title}' /></br>
</div>
<div class="left">
{$data}
</div>
</article>
EOD;

/**
 * Finally, leave it to the rendering phase of en4fil
 */
include(EN4FIL_THEME_PATH);
