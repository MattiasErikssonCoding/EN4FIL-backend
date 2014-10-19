<?php

include(__DIR__.'/config.php');

$db = new CDatabase($en4fil['database']);

// isset-params
$title    	= isset($_GET['title']) ? $_GET['title'] : null;
$console    	= isset($_GET['console']) ? $_GET['console'] : null;
$data   = isset($_POST['DATA'])  ? $_POST['DATA']  : array();
$hits     	= isset($_GET['hits'])  ? $_GET['hits']  : 10;
$page     	= isset($_GET['page'])  ? $_GET['page']  : 1;
$year1    	= isset($_GET['year1']) && !empty($_GET['year1']) ? $_GET['year1'] : null;
$year2    	= isset($_GET['year2']) && !empty($_GET['year2']) ? $_GET['year2'] : null;

$acronym = isset($_SESSION['user']) ? $_SESSION['user']->acronym : null;

$orderby  	= isset($_GET['orderby']) ? strtolower($_GET['orderby']) : 'title';
$order    	= isset($_GET['order'])   ? strtolower($_GET['order'])   : 'asc';
// Check that incoming parameters are valid
is_numeric($hits) or die('Check: Hits must be numeric.');
is_numeric($page) or die('Check: Page must be numeric.');
is_numeric($year1) || !isset($year1)  or die('Check: Year must be numeric or not set.');
is_numeric($year2) || !isset($year2)  or die('Check: Year must be numeric or not set.');

// Prepare the query based on incoming arguments
$OmniSQL	= 'SELECT image, price, console, title, YEAR, DATA, id FROM Games';
$tweak		= null;
$groupby	= ' GROUP BY title';
$limit		= null;
$sort		= " ORDER BY $orderby $order";
$params		= array();

// Select by name
if($title) {
	$tweak .= ' AND title LIKE ?';
  	$params[] = $name;
}

if($console) {
	$tweak .= ' AND console LIKE ?';
  	$params[] = $console;
}

// Select by year
if($year1) {
  	$tweak .= ' AND YEAR >= ?';
  	$params[] = $year1;
}

if($year2) {
  	$tweak .= ' AND YEAR <= ?';
  	$params[] = $year2;
} 

// Pagination
if($hits && $page) {
  	$limit = " LIMIT $hits OFFSET " . (($page - 1) * $hits);
}

// Complete the sql statement
$tweak = $tweak ? " WHERE 1 {$tweak}" : null;
$sql = $OmniSQL . $tweak . $groupby . $sort . $limit;
$res = $db->QueryFetch($sql, $params);

// Put results into a HTML-table
$tr = "<tr>
<th>Namn </th>
<th>Bildlänk </th>
<th>Konsol </th>
<th>Pris </th>
<th>Year </th>
<th>Edit </th>
<th>Delete </th>
</tr>";

foreach($res AS $key => $val) {
	$tr .= "<tr>
	<td>{$val->title}</td>
	<td>{$val->image}</td>
	<td>{$val->console}</td>
	<td>{$val->price}</td>
	<td>{$val->YEAR}</td>
	<td><a href=games_edit.php?id={$val->id}><button type='button'>Edit</button></a></td>
	<td><a href=games_delete.php?id={$val->id}><button type='button'>Delete</button></a></td>
	</tr>";
}


$sql = "SELECT COUNT(Id) AS rows FROM ($OmniSQL $tweak $groupby) AS Games";
$res = $db->QueryFetch($sql, $params);
$rows = $res[0]->rows;
$max = ceil($rows / $hits);

/*
 *Generate HTML etc....
 */
$en4fil['title'] = "Speldatabasen";

$hitsPerPage = $db->getHitsPerPage(array(10, 20, 30), $hits);
$navigatePage = $db->getPageNavigation($hits, $page, $max);
$editLink = $acronym ? "<a href='games_create.php'>Skapa spelinlägg</a>" : null;


$en4fil['main'] = <<<EOD

<form>
  	<fieldset>
  		<legend>Spel som går att hyra</legend>
  		<input type=hidden name=hits value='{$hits}'/>
  		<input type=hidden name=page value='1'/>
  		<p><label>Namn: <input type='search' name='title' value='{$title}'/>
  		<p><label>Konsol: <input type='search' name='console' value='{$console}'/>
		<p>Använd % som wildcard</p>
		</label></p>
  		<p><label>Sök på år:
      			<input type='text' name='year1' value='{$year1}'/></label>
      			- 
      			<label><input type='text' name='year2' value='{$year2}'/></label>  
  		</p>
  		<p><input type='submit' name='submit' value='Sök'/></p>
  		<p><a href='?'>Nollställ sökparametrar</a></p>
{$editLink}
	</fieldset>
</form>

{$rows} träffar. {$hitsPerPage}
<div class="database">
<table>
	{$tr}
</table>
</div>
{$navigatePage}

EOD;
 
/**
 * Finally, leave it to the rendering phase of en4fil.
 *
 */
include(EN4FIL_THEME_PATH); 
