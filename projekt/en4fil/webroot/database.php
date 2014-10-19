<?php

include(__DIR__.'/config.php');

$db = new CDatabase($en4fil['database']);

// isset-params
$title    	= isset($_GET['title']) ? $_GET['title'] : null;
$console    	= isset($_GET['console']) ? $_GET['console'] : null;
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
$OmniSQL	= 'SELECT image, price, console, title, YEAR, id FROM Games';
$tweak		= null;
$groupby	= ' GROUP BY title';
$limit		= null;
$sort		= " ORDER BY $orderby $order";
$params		= array();

// Select by name
if($title) {
	$tweak .= ' AND title LIKE ?';
  	$params[] = $title;
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
<th>Bild</th>
<th>Namn ".$db->orderby('title')."</th>
<th>Konsol ".$db->orderby('console')."</th>
<th>År ".$db->orderby('YEAR')."</th>
<th>Pris ".$db->orderby('price')."</th>
<th></th>
</tr>";

foreach($res AS $key => $val) {
	$tr .= "<tr>
	<td><img src='img.php?src={$val->image}&save-as=jpg&width=100&height=100&sharpen' alt='{$val->title}' /></td>
	<td><a href='games_page.php?id={$val->id}'>{$val->title}</a></td>
	<td>{$val->console}</td>
	<td>{$val->YEAR}</td>
	<td>{$val->price}:-</td>
	<td><a href=hyr.php?url={$val->title}><button type='button'>Hyr spel</button></a></td>
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

$hitsPerPage = $db->getHitsPerPage(array(10, 20, 30, 100), $hits);
$navigatePage = $db->getPageNavigation($hits, $page, $max);

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
  		<p><a href='?'><button type="button">Nollställ sökparametrar</button></a></p>
	</fieldset>
</form></br>
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
