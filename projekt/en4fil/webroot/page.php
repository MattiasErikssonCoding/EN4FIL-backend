<?php 
include(__DIR__.'/config.php'); 

$en4fil['title'] = "Pages";

/**
 * Connect to a MySQL database using PHP PDO
 */ 
$db = new CDatabase($en4fil['database']);
$page = new CPage($db);
$filtertext = new CTextFilter();

$res = $page->getPage();

$acronym = isset($_SESSION['user']) ? $_SESSION['user']->acronym : null;
$editLink = $acronym ? "<a href='edit.php?id={$res->id}'>Uppdatera</a>" : null;

$title  = htmlentities($res->title, null, 'UTF-8');
$data   = $filtertext->doFilter(htmlentities($res->DATA, null, 'UTF-8'), $res->FILTER);

/*
 * Execute and store in variables in the en4fil container.
 */ 

$en4fil['title'] = $title;
$enfil['debug'] = $db->Dump();

$en4fil['main'] = <<<EOD
<article class="readable">
<h1>{$en4fil['title']}</h1>
{$data}
</article>
EOD;

/**
 * Finally, leave it to the rendering phase of en4fil
 */
include(EN4FIL_THEME_PATH);
