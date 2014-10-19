<?php

class CPage extends CContent {

    	public function __construct($db) {
        	parent::__construct($db);
    	}

    	public function getPage() {
        	$url = isset($_GET['url']) ? $_GET['url'] : null;
        	$res = $this->selectPageWithUrl($url);
        	return $res;
    	}
}
