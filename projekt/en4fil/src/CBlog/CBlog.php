<?php 

class CBlog extends CContent { 

    	public function __construct($db) { 
        	parent::__construct($db); 
    	} 

    	public function getBlog() { 
        	$slug = isset($_GET['slug']) ? $_GET['slug'] : null; 
        	$c = $this->selectPostWithSlug($slug);
        	return $c; 
    	} 
} 
