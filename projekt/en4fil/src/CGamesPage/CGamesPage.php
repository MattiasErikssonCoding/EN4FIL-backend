<?php

class CGamesPage extends CGames {

    	public function __construct($db) {
        	parent::__construct($db);
    	}

	public function getGame() {
        	$id = isset($_GET['id']) ? $_GET['id'] : null;
        	$res = $this->selectGameWithId($id);
        	return $res;
    	}
}
