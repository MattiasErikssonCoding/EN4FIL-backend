<?php 
include(__DIR__.'/config.php'); 

function getUrlToContent($content) {
  	switch($content->TYPE) {
    		case 'page': return "page.php?url={$content->url}"; break;
    		case 'post': return "nyheter.php?slug={$content->slug}"; break;
    		default: return null; break;
  	}
}

// Connect to a MySQL database using PHP PDO  
$db = new CDatabase($en4fil['database']);  
$data = new CTextFilter('doFilter'); 

// Get all content
$sql = 'SELECT *, (published <= NOW()) AS available FROM News;';
$res = $db->QueryFetch($sql);

// Put results into a list  
$items = null;
foreach($res AS $key => $val) {
  	$items .= "<li>{$val->TYPE} (".(!$val->available ? 'inte ' : null)."publicerad): ".htmlentities($val->title, null, 'UTF-8')."(<a href='edit.php?id={$val->id}'>editera</a> <a href='".getUrlToContent($val)."'>visa</a> <a href='delete.php?id={$val->id}'>ta bort</a>)</li>\n"; 
}

// Do it and store it all in variables in the en4fil container.  
$en4fil['title'] = "Inlägg";   

$en4fil['main'] = <<<EOD
<div id='view'>
<h1>Inlägg</h1>

<ul>
{$items}
</ul>
<p><a href='create.php'>Skapa nytt inlägg</a></p>  
</div>
EOD;

// Finally, leave it all to the rendering phase of en4fil 
include(EN4FIL_THEME_PATH); 
