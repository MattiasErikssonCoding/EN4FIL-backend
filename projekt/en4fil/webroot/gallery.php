<?php 

// Include the essential config-file which also creates the $joax variable with its defaults. 
include(__DIR__.'/config.php');  


$en4fil['stylesheets'][] = 'css/figure.css';  
$en4fil['stylesheets'][] = 'css/gallery.css';  
$en4fil['stylesheets'][] = 'css/breadcrumb.css'; 

$gallery = new CGallery($validImages); 

//  
// Define the basedir for the gallery  
//  
define('GALLERY_PATH', __DIR__ . DIRECTORY_SEPARATOR . 'img'); 
define('GALLERY_BASEURL', ''); 

//  
// Get incoming parameters  
//  
$path = isset($_GET['path']) ? $_GET['path'] : null; 
$pathToGallery = realpath(GALLERY_PATH . '/' . $path); 

//  
// Validate incoming arguments  
//  
is_dir(GALLERY_PATH) or $gallery->errorMessage('This URL is not a valid Gallery-URL.');
substr_compare(GALLERY_PATH, $pathToGallery, 0, strlen(GALLERY_PATH)) == 0 or $gallery->errorMessage('Security constraint: Source gallery is not directly below the directory GALLERY_PATH.');  

//  
// Read and present images in the current directory  
//  
if(is_dir($pathToGallery)) {  
	$galleri = $gallery->readAllItemsInDir($pathToGallery);  
}else if(is_file($pathToGallery)) {  
  	$galleri = $gallery->readItem($pathToGallery);  
}  

// Do it and store it all in variables in the Joax container. 
$breadcrumb = $gallery->createBreadcrumb($pathToGallery); 

$en4fil['title'] = "Exempel på galleri av bilder med img.php"; 

$en4fil['main'] = <<<EOD
<h1>{$en4fil['title']}</h1> 

$breadcrumb 
  
$galleri 

EOD;

// Finally, leave it all to the rendering phase of en4fil
include(EN4FIL_THEME_PATH);
