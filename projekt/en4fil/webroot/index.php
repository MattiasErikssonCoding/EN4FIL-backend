<?php 
/**
 * This is a en4fil pagecontroller.
 *
 */
// Include the essential config-file which also creates the $en4fil variable with its defaults.
include(__DIR__.'/config.php'); 

$db = new CDatabase($en4fil['database']);

// Do SELECT from a table
$sql = "SELECT id, image, title, YEAR FROM Games ORDER BY CT DESC LIMIT 3;";
$params = array();
$res = $db->QueryFetch($sql, $params);

// Put results into a HTML-table
$tr = "<tr><th>Bild</th><th>Titel</th><th>År</th></tr>";
foreach($res AS $key => $val) {
  $tr .= "<tr>
            <td><img src='img.php?src={$val->image}&save-as=jpg&width=100' alt='{$val->title}' /></td>
            <td><a href='games_page.php?id={$val->id}'>{$val->title}</a></td>
            <td>{$val->YEAR}</td>
        </tr>";
}


// Do it and store it all in variables in the en4fil container.
$en4fil['title'] = "My me-site";
$en4fil['main'] = <<<EOD
<div class="right">
<h3>Senast inkomna spel </h3>
<table>
	{$tr}
</table>
</div>

<div class="left">
	<p>Väkommen till RetroGameRental. Vi är en grupp människor från den äldre generationen som specialiserar sig i att restaurera äldre spelkonsoler och datorer. Vi tycker att den nya generationen, hur vacker grafiken än är, saknar något som enbart dom äldre spelkonsolerna har.</p>
<p>Vi har flertalet spel från gårdagens spelhyllor som går att hyra i våran butik, och mer information om oss kan du hitta under fliken "Om Oss".</p>
</div>
EOD;
// Finally, leave it all to the rendering phase of en4fil.
include(EN4FIL_THEME_PATH);
?>
