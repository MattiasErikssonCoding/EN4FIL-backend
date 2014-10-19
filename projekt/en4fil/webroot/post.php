<?php
/*
 * First add essential config-file which also creates the $raket variable with its defaults.
 */
include(__DIR__.'/config.php'); 

/**
 * Connect to a MySQL database using PHP PDO
 *
 */
$blog = new CBlog($en4fil['database']);

// Get parameters 
$slug    = isset($_GET['slug']) ? $_GET['slug'] : null;

if($slug) {
	$res = $blog->getBlogContentForSlug($slug); 
}else {
    	$res = $blog->getAllBlogContent();
}

/**
 * Execute and store in variables in the Raket container.
 */

$en4fil['title'] = "Pages";

if(isset($res[0])) {
	foreach($res as $val) {
        	$title  = htmlentities($val->title, null, 'UTF-8');
        	$date = date_format(date_create($val->created), 'j M');
        	$title .= ' - ' .htmlentities($date, null, 'UTF-8');
        	$data   = $blog->doFilter(htmlentities($val->data, null, 'UTF-8'), $val->filter);

$en4fil['main']= <<<EOD
       <h1>{$title}</h1>
       <p>{$data}</p>
       <p><a href="blog.php">Tillbaka</a></p>
EOD;
 	}
}else if($slug) {
  	$en4fil['main'] = "Den bloggposten finns inte.";
}else {
  	$en4fil['main'] = "Det finns inga bloggposter.";
}

/**
 * Finally, leave it to the rendering phase of en4fil
 *
 */
include(EN4FIL_THEME_PATH); 
