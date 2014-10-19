<?php
date_default_timezone_set('Europe/Stockholm');

/**
 * Class for working with text content
 */
class CContent {

	private $db;

	public function __construct($db) {
     		$this->db=$db;
    	}

	public function restore() {  
    		$sql="  
    		DROP TABLE IF EXISTS News;  
    		CREATE TABLE News (  
    		id INT AUTO_INCREMENT PRIMARY KEY NOT NULL,  
    		slug CHAR(80) UNIQUE,  
    		url CHAR(80) UNIQUE,  
    		user char(12),  
    		TYPE CHAR(80),  
    		title VARCHAR(80),  
    		DATA TEXT,  
    		FILTER CHAR(80),  
   
    		published DATETIME,  
    		created DATETIME,  
    		updated DATETIME,  
    		deleted DATETIME 
		) ENGINE INNODB CHARACTER SET utf8;";

		try{  
  			$this->db->ExecuteQuery($sql);  
 		}  
 		catch (Exception $e) {   
     			$ouput=null;
 		}

		$sql= "INSERT INTO News (user,slug, url, TYPE, title, DATA, FILTER, published, created) VALUES  
    		('admin', 'hem','hem', 'post', 'Hem', 'Detta är ett inlägg som bevisar att databasen har blivit återställd och att den fungerar. Det är skrivet i [url=http://en.wikipedia.org/wiki/BBCode]bbcode[/url] vilket innebär att man kan formattera texten till [b]bold[/b] och [i]kursiv stil[/i] samt hantera länkar.\n\nDessutom finns ett filter nl2br som lägger in <br>-element istället för \\n, det är smidigt, man kan skriva texten precis som man tänker sig att den skall visas, med radbrytningar.', 'bbcode,nl2br', NOW(), NOW());";

		try{
  			$this->db->ExecuteQuery($sql);  
 		}  
 		catch (Exception $e) {   
     			$ouput=null;
 		}
  			$output = "Nyheterna är återställda";  
  		return $output;  
	}  

	public function edit() {
	// Get parameters   
		$id     = isset($_POST['id'])    ? strip_tags($_POST['id']) : (isset($_GET['id']) ? strip_tags($_GET['id']) : null);  
		$title  = isset($_POST['title']) ? $_POST['title'] : null;  
		$slug   = isset($_POST['slug'])  ? $_POST['slug']  : null;  
		$url    = isset($_POST['url'])   ? strip_tags($_POST['url']) : null;  
		$data   = isset($_POST['DATA'])  ? $_POST['DATA'] : array();  
		$type   = isset($_POST['TYPE'])  ? strip_tags($_POST['TYPE']) : array();  
		$filter = isset($_POST['FILTER']) ? $_POST['FILTER'] : array();  
		$published = isset($_POST['published'])  ? strip_tags($_POST['published']) : array();  
		$save   = isset($_POST['save'])  ? true : false;  
		$acronym = isset($_SESSION['user']) ? $_SESSION['user']->acronym : null;  

		$sql = '  
    			UPDATE News SET  
      			title   = ?,  
      			slug    = ?,  
      			url     = ?,  
      			DATA    = ?,  
      			TYPE    = ?,  
      			FILTER  = ?,  
      			published = ?,  
      			updated = NOW()  
    			WHERE   
      			id = ?';
  
  		$params = array($title, $slug, $url, $data, $type, $filter, $published, $id);  
  		$this->db->ExecuteQuery($sql, $params);  
  		$output = 'Informationen sparades.';  
  		return $output;  
	}  

	public function CreateContent($title) {
  		$sql = 'INSERT INTO News (title, user) VALUES (?,?)';  
  		$acronym = isset($_SESSION['user']) ? $_SESSION['user']->acronym : null;  
  		$params = array($title,$acronym);  
  		$this->db->ExecuteQuery($sql,$params); 
		$id = $this->db->LastInsertId(); 
  		header('Location: edit.php?id=' . $this->db->LastInsertId());  
	}  

	public function deleteContent($id) {  
   		$sql = 'DELETE FROM News WHERE id = ? LIMIT 1';  
   		$params = array($id);  
   		$this->db->ExecuteQuery($sql, $params);  
   		$output="Det raderades " . $this->db->RowCount() . " rader från nyhetsdatabasen.";  
   		return $output;  
	}  

	public function selectWithId($id) {  
    		// Select from database  
    		$sql = 'SELECT * FROM News WHERE id = ?';  
    		$res = $this->db->QueryFetch($sql, array($id));  
        	
		if(isset($res[0])) {  
            		$c = $res[0];  
            		return $c;  
        	}else {  
        		die('Misslyckades: det finns inget innehåll med id '.$id);  
            		return null;  
        	}  
	}  

	public function selectPageWithUrl($url) {  
   		// Get content  
		$sql = "SELECT * FROM News WHERE TYPE = 'page' AND url = ? AND published <= NOW();";
		$res = $this->db->QueryFetch($sql, array($url));
		if(isset($res[0])) {  
  			$c = $res[0];  
  			return $c;  
		}else {  
  			die('Misslyckades: det finns inget innehåll.');
		}  
	}
  
	public function selectPostWithSlug($slug) {  
    		// Get content  
    		$slugSql = $slug ? 'slug = ?' : '1';  
    		$sql = "  
    		SELECT * FROM News WHERE  
        		TYPE = 'post' AND  
        		$slugSql AND  
        		published <= NOW()  
    		ORDER BY updated DESC;";  
    		$res = $this->db->QueryFetch($sql, array($slug));  
    		return $res;
    	}
  
    	/**
    	 * Create a slug of a string, to be used as url.
    	 *
    	 * @param string $str the string to format as slug.
    	 * @returns str the formatted slug. 
    	 */
    	public function slugify($str) {
        	$str = mb_strtolower(trim($str));
        	$str = str_replace(array('å','ä','ö'), array('a','a','o'), $str);
        	$str = preg_replace('/[^a-z0-9-]/', '-', $str);
        	$str = trim(preg_replace('/-+/', '-', $str), '-');
        	return $str;
    	}
}
