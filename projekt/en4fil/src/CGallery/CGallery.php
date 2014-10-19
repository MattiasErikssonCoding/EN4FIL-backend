<?php  

class cGallery { 

	/** 
	 * Display error message. 
	 * 
	 * @param string $message the error message to display. 
	 */ 
	function errorMessage($message) { 
  		header("Status: 404 Not Found"); 
  		die('gallery.php says 404 - ' . htmlentities($message));
	}

 	/** 
 	 * Read directory and return all items in a ul/li list. 
	 * 
	 * @param string $path to the current gallery directory. 
	 * @param array $validImages to define extensions on what are considered to be valid images. 
	 * @return string html with ul/li to display the gallery. 
	 */ 
	function readAllItemsInDir($path, $validImages = array('png', 'jpg', 'jpeg', 'gif')) { 
  		$files = glob($path . '/*');  
  		$gallery = "<ul class='gallery'>\n"; 
  		$len = strlen(GALLERY_PATH); 
  
  		foreach($files as $file) { 
    			$parts = pathinfo($file); 
  
    			// Is this an image or a directory 
    			if(is_file($file) && in_array($parts['extension'], $validImages)) { 
      				$item    = "<img alt='' src='img.php?src="
					. GALLERY_BASEURL . substr($file, $len +1) . 
					"&amp;width=80&amp;height=150&amp;crop-to-fit' />"; 
      				$caption = basename($file);  
    			}else if(is_dir($file)) { 
      				$item    = "<img src='img/folder.png' alt=''/>"; 
      				$caption = basename($file) . '/'; 
    			}else { 
      				continue; 
    			} 
  
    			// Avoid to long captions breaking layout 
    			$fullCaption = $caption; 
    			if(strlen($caption) > 18) { 
      				$caption = substr($caption, 0, 10) . '…' . substr($caption, -5); 
    			} 
  
    			$href = substr($file, $len + 1); 
    			$gallery .= "<li><a href='?path={$href}' title='
			{$fullCaption}'><figure class='figure overview'>
			{$item}<figcaption>
			{$caption}</figcaption></figure></a></li>\n"; 
  		} 
  		$gallery .= "</ul>\n";
  		return $gallery; 
	}

	/**
	 * Read and return info on choosen item. 
	 * 
	 * @param string $path to the current gallery item. 
	 * @param array $validImages to define extensions on what are considered to be valid images. 
	 * @return string html to display the gallery item. 
	 */ 
	function readItem($path, $validImages = array('png', 'jpg', 'jpeg', 'gif')) { 
  		$parts = pathinfo($path); 
  		if(!(is_file($path) && in_array($parts['extension'], $validImages))) { 
    			return "<p>This image is an invalid format for this gallery."; 
  		}

  		// Get info on image 
  		$imgInfo = list($width, $height, $type, $attr) = getimagesize($path); 
  		$mime = $imgInfo['mime']; 
  		$gmdate = gmdate("D, d M Y H:i:s", filemtime($path)); 
  		$filesize = round(filesize($path) / 1024);  
  
  		// Get constraints to display original image 
  		$displayWidth  = $width > 400 ? "&amp;width=400" : null; 
  		$displayHeight = $height > 300 ? "&amp;height=300" : null; 
  
  		// Display details on image 
  		$len = strlen(GALLERY_PATH); 
  		$href = GALLERY_BASEURL . substr($path, $len +1); 

$item = <<<EOD
	<p><img src='img.php?src={$href}{$displayWidth}{$displayHeight}' alt=''/></p> 
	<p>Original image dimensions are {$width}x{$height} pixels. <a href='img.php?src={$href}'>View original image</a>.</p> 
	<p>File size is {$filesize}KBytes.</p> 
	<p>Image has mimetype: {$mime}.</p> 
	<p>Image was last modified: {$gmdate} GMT.</p> 
EOD;
		
	return $item; 
	} 

	/** 
	 * Create a breadcrumb of the gallery query path. 
	 * 
	 * @param string $path to the current gallery directory. 
	 * @return string html with ul/li to display the thumbnail. 
	 */ 
	function createBreadcrumb($path) { 
  		$parts = explode('/', trim(substr($path, strlen(GALLERY_PATH) + 1), '/')); 
  		$breadcrumb = "<ul class='breadcrumb'>\n<li><a href='?'>Hem</a> »</li>\n"; 
  
  		if(!empty($parts[0])) { 
    			$combine = null; 
    			foreach($parts as $part) { 
      				$combine .= ($combine ? '/' : null) . $part; 
      				$breadcrumb .= "<li><a href='?path={$combine}'>$part</a> » </li>\n"; 
    			} 
  		} 
  
  		$breadcrumb .= "</ul>\n"; 
  		return $breadcrumb; 
	} 

}
