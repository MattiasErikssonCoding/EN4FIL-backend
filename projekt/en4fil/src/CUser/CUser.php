<?php

class CUser {
  /*es  
   *  
   */ 
	protected $db; 
    	public $user;  
    	
	/**  
   	 * Constructor  
   	 *  
   	 */  
    	public function __construct($db) {  
    		$this->db=$db;  
    	}
     
    	/*
    	 * Check if user is authenticated.
    	 *
    	 */
    	function IsAuthenticated() {
		if(isset($_SESSION['user'])){ 
            		return true; 
            	}else { 
            		return false; 
            	} 
        }  


    	/*
    	 *Give feedback to user
    	 *
    	 */
    	function AuthenticateOutput() {
        	$acronym = isset($_SESSION['user']) ? $_SESSION['user']->acronym : null;
        	if($acronym) {
            		$output = "Du är inloggad som {$_SESSION['user']->name}.";
            	}else {
            		$output = "Du är inte inloggad.";
            	}
        	return $output;
        }
 
    	/** 
    	 * Login the user 
    	 *
    	 */ 
    	function login($user,$password) { 
        	$sql = "SELECT acronym, name FROM USER WHERE acronym = ? AND password = md5(concat(?, salt))"; 
        	$params = array(); 
        	$params=[htmlentities($user), htmlentities($password)]; 
        	$res=$this->db->QueryFetch($sql, $params); 
        	if(isset($res[0])) { 
            		$_SESSION['user'] = $res[0]; 
            		return true; 
            	}else { 
            		return false; 
            	}  
        }

    	/** 
     	 * Logout the user 
    	 *
    	 */ 
    	function logoutUser() { 
        	unset($_SESSION['user']); 
        }  
}
