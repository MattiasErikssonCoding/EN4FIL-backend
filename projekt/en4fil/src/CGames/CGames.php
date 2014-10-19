<?php
date_default_timezone_set('Europe/Stockholm');

/**
 * Class for working with text content
 */
class CGames {

	private $db;

	public function __construct($db) {
     		$this->db=$db;
    	}

	public function RestoreGames() {  
    		$sql="  
    		DROP TABLE IF EXISTS Games;  
    		CREATE TABLE Games (  
    		id INT AUTO_INCREMENT PRIMARY KEY NOT NULL,  
		image VARCHAR(100),  
    		price INT,
		console varchar(80),
    		title VARCHAR(80),  
    		YEAR INT,
		) ENGINE INNODB CHARACTER SET utf8;";

		try{  
  			$this->db->ExecuteQuery($sql);  
 		}  
 		catch (Exception $e) {   
     			$ouput=null;
 		}

                $sql= "INSERT INTO Games (image, price, console, title, YEAR) VALUES
			(('Asteroids_2600',29,'Atari 2600','Asteroids', 1981), ('Adventure_2600',29,'Atari 2600','Adventure', 1979), ('Breakout_2600',29,'Atari 2600','Breakout', 1978))";
 		try  
 		{ 
  			$this->db->ExecuteQuery($sql);  
 		}  
 		catch (Exception $e)  
 		{   
     		$ouput=null;
 		}

		$output = "Databasen är återställd";  
  		return $output;  
	}  

	public function EditGame() {
	// Get parameters   
		$id     = isset($_POST['id'])    ? strip_tags($_POST['id']) : (isset($_GET['id']) ? strip_tags($_GET['id']) : null);  
		$image  = isset($_POST['image']) ? $_POST['image'] : null;  
		$price  = isset($_POST['price']) ? $_POST['price'] : null;  
		$console  = isset($_POST['console']) ? $_POST['console'] : null;  
		$title  = isset($_POST['title']) ? $_POST['title'] : null;  
		$year  = isset($_POST['YEAR']) ? $_POST['YEAR'] : null;  
         	$data   = isset($_POST['DATA'])  ? $_POST['DATA']  : array();
		$save   = isset($_POST['save'])  ? true : false;  
		$acronym = isset($_SESSION['user']) ? $_SESSION['user']->acronym : null;  

		$sql = '  
    			UPDATE Games SET  
			DATA	= ?,  	
			image	= ?,  	
      			price   = ?,  
      			console   = ?,  
      			title   = ?,  
      			YEAR   = ?  
    			WHERE   
      			id = ?';
  
  		$params = array($data, $image, $price, $console, $title, $year, $id);  
  		$this->db->ExecuteQuery($sql, $params);  
  		$output = 'Informationen sparades.';  
  		return $output;
	}  

	public function CreateGame($title) {
  		$sql = 'INSERT INTO Games (title) VALUES (?)';  
  		$params = array($title);  
  		$this->db->ExecuteQuery($sql,$params); 
		$id = $this->db->LastInsertId();
  		header('Location: games_edit.php?id=' . $this->db->LastInsertId());  
	}  

	public function deleteGame($id) {  
   		$sql = 'DELETE FROM Games WHERE id = ? LIMIT 1';  
   		$params = array($id);  
   		$this->db->ExecuteQuery($sql, $params);  
   		$output="Det raderades " . $this->db->RowCount() . " rader från tabellen 'Games'.";  
   		return $output;
	}  

	public function selectGameWithId($id) {  
    		// Select from database  
    		$sql = 'SELECT * FROM Games WHERE id = ?';  
    		$res = $this->db->QueryFetch($sql, array($id));  
        	
		if(isset($res[0])) {  
            		$c = $res[0];  
            		return $c;  
        	}else {  
        		die('Misslyckades: det finns inget innehåll med id '.$id);  
            		return null;  
        	}  
	}  
}
