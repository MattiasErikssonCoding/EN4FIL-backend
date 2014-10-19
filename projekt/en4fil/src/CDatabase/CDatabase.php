<?php
//databaseclass
class CDatabase {

	private $options;
	private $db = null;
	private $stmt = null;
	private static $numQueries = 0;
	private static $queries = array();
	private static $params = array();

	//Constructor creating PDO

	public function __construct($options=NULL) {
		
		$default = array(
			'dsn' => null,
			'username' => null,
			'password' => null,
			'driver_options' => null,
			'fetch_style' => PDO::FETCH_OBJ,
		);
		$this->options = array_merge($default, $options);

		try {
			$this->db = new PDO(
			$this->options['dsn'], 
			$this->options['username'], 
			$this->options['password'], 
			$this->options['driver_options']);
		}
		catch (Exception $e) {
			throw $e;
			throw new PDOException('Unable to connect to database. Techmonkeys to the resque!');
		}
		$this->db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, $this->options['fetch_style']);
	}

	//Executes a query, and ignores the result

	public function QueryFetch($query, $params=array(), $debug=false) {
		self::$queries[] = $query;
		self::$params[] = $params;
		self::$numQueries++;

		if($debug) {
			echo "<p> Query = <br/><pre>{$query}</pre></p>
			<p>Num query = ".self::$numQueries."</p>
			<p><pre>".print_r($params, 1)."</pre></p>";
		}

		$this->stmt = $this->db->prepare($query);
		$this->stmt->execute($params);
		return $this->stmt->fetchAll();
	}

	//Returns the last insert id

	public function LastInsertId() {
		return $this->db->lastInsertid();
	}

	/*
	 * Method to log database queries
	 */

	public function QueryLog() {
		$html = '<p><i>You have made '.self::$numQueries.' database queries.</i></p><pre>';
		foreach(slef::$queries as $key => $val) {
			$params = empty(self::$params[$key]) ? null : 
			htmlentities(print_r(self::$params[$key], 1)).'<br/></br>';
		$html .= $val.'<br/></br>'.$params;
		}
	return $html.'</pre>';
	}

	//Returns row last affected

	public function RowCount() {
		return is_null($this->stmt) ? $this->stmt : $this->stmt->rowCount();
	}
	
	public function ExecuteQuery($query, $params = array(), $debug=false) {
 
    		self::$queries[] = $query; 
    		self::$params[]  = $params; 
    		self::$numQueries++;
 
    		if($debug) {
      			echo "<p>Query = <br/><pre>{$query}</pre></p><p>Num query = ".self::$numQueries."</p><p><pre>".print_r($params, 1)."</pre></p>";
    		}
    		$this->stmt = $this->db->prepare($query);
    		return $this->stmt->execute($params);
 	}

	public function SaveDebug($debug=null) {
    		if($debug) {
      			self::$queries[] = $debug;
      			self::$params[] = null;
    		}
 
    		self::$queries[] = 'Saved debuginformation to session.';
    		self::$params[] = null;
 
 	   	$_SESSION['CDatabase']['numQueries'] = self::$numQueries;
    		$_SESSION['CDatabase']['queries']    = self::$queries;
    		$_SESSION['CDatabase']['params']     = self::$params;
  	}

	/* Use the current querystring as base, modify it according to $options and return the modified query string.
	 *
	 * @param array $options to set/change.
	 * @param string $prepend this to the resulting query string
	 * @return string with an updated query string.
	 */
    	function getQueryString($options=array(), $prepend='?') {
    		// parse query string into array
        	$query = array();
        	parse_str($_SERVER['QUERY_STRING'], $query);

    		// Modify the existing query string with new options
        	$query = array_merge($query, $options);

    		// Return the modified querystring
        	return $prepend . htmlentities(http_build_query($query));
    	}


	/**
	 * Create links for hits per page.
	 *
	 * @param array $hits a list of hits-options to display.
	 * @param array $current value.
	 * @return string as a link to this page.
	 */
    	function getHitsPerPage($hits, $current=null) {
        	$nav = "Träffar per sida: ";
        	foreach($hits AS $val) {
            		if($current == $val) {
                		$nav .= "$val ";
            		}else {
                		$nav .= "<a href='" . $this->getQueryString(array('hits' => $val)) . "'>$val</a> ";
            		}
        	}  
    		return $nav;
    	}

	/**
	 * Create navigation among pages.
	 *
	 * @param integer $hits per page.
	 * @param integer $page current page.
	 * @param integer $max number of pages. 
	 * @param integer $min is the first page number, usually 0 or 1. 
	 * @return string as a link to this page.
	 */
	function getPageNavigation($hits, $page, $max, $min=1) {
        	$nav  = ($page != $min) ? "<a href='" . $this->getQueryString(array('page' => $min)) . "'>&lt;&lt;</a> " : '&lt;&lt; ';
        	$nav .= ($page > $min) ? "<a href='" . $this->getQueryString(array('page' => ($page > $min ? $page - 1 : $min) )) . "'>&lt;</a> " : '&lt; ';

        	for($i=$min; $i<=$max; $i++) {
        		if($page == $i) {
            			$nav .= "$i ";
            		}else {
            			$nav .= "<a href='" . $this->getQueryString(array('page' => $i)) . "'>$i</a> ";
            		}
        	}

        	$nav .= ($page < $max) ? "<a href='" . $this->getQueryString(array('page' => ($page < $max ? $page + 1 : $max) )) . "'>&gt;</a> " : '&gt; ';
        	$nav .= ($page != $max) ? "<a href='" . $this->getQueryString(array('page' => $max)) . "'>&gt;&gt;</a> " : '&gt;&gt; ';
        	return $nav;
	}


	/**
	 * Function to create links for sorting
	 *
	 * @param string $column the name of the database column to sort by
	 * @return string with links to order by column.
	 */
	function orderby($column) {
        	$nav  = "<a href='" . $this->getQueryString(array('orderby'=>$column, 'order'=>'asc')) . "'>&darr;</a>";
        	$nav .= "<a href='" . $this->getQueryString(array('orderby'=>$column, 'order'=>'desc')) . "'>&uarr;</a>";
        	return "<span class='orderby'>" . $nav . "</span>";
    	}

        public function Dump() {
    		$html  = '<p><i>Du har gjort ' . self::$numQueries . ' databas querie(s).</i></p><pre>';
    		foreach(self::$queries as $key => $val) {
      			$params = empty(self::$params[$key]) ? null : htmlentities(print_r(self::$params[$key], 1)) . '<br/></br>';
      			$html .= $val . '<br/></br>' . $params;
    		}
    		return $html . '</pre>';
  	}
}
