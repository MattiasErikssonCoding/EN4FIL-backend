<?php
include(__DIR__.'/config.php'); 

$en4fil['stylesheet'] = '/css/style.css';
 
/**
 * Set up database connection
 *
 */
$db = new CDatabase($en4fil['database']);
$blog = new CBlog($db);
$filter = new CTextFilter();

$res = $blog->getBlog();

$slug = isset($_GET['slug']) ? $_GET['slug'] : null;
$acronym = isset($_SESSION['user']) ? $_SESSION['user']->acronym : null;

$en4fil['title'] = "Nyheter";
$en4fil['debug'] = $db->Dump();
$en4fil['main'] = null;

/**
 * Get all content and display in list
 */ 
if(isset($res[0])) {  
	foreach($res as $c) {  
    		// Sanitize content before using it.  
    		$title  	= htmlentities($c->title, null, 'UTF-8');  
    		$data   	= $filter->doFilter(htmlentities($c->DATA, null, 'UTF-8'), $c->FILTER);   
		$created	= htmlentities($c->updated, null, 'UTF-8');

    		if($slug) {
      			$en4fil['title'] = "$title | " . $en4fil['title'];  
    		}

    		$editLink = $acronym ? "<a href='create.php'>Skapa ny post</a> <a href='edit.php?id={$c->id}'>Uppdatera posten</a> <a href='delete.php?id={$c->id}'>Ta bort posten</a>" : null;  

$en4fil['main'] .= <<<EOD
<div id='blog'>
	<article>
		<header>
  				<h2><a href='nyheter.php?slug={$c->slug}'>{$title}</a></h2>  
		</header>
			{$data} 
			 
		<footer>
			{$editLink}
			<p>{$created}</p>
		</footer>  
	</article>  
</div>
EOD;
}
}else if($slug) {  
	$en4fil['main'] = "Det fanns inte en sådan nyhet.";  
}else {  
  	$en4fil['main'] = "Det fanns inga nyheter.";  
}

// Finally, leave it all to the rendering phase of en4fil
include(EN4FIL_THEME_PATH);  
