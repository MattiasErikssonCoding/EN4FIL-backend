<?php 
/**
 * This is a en4fil pagecontroller.
 *
 */
// Include the essential config-file which also creates the $en4fil variable with its defaults.
include(__DIR__.'/config.php'); 


// Do it and store it all in variables in the en4fil container.

$en4fil['title'] = "My me-site";

$en4fil['main'] = <<<EOD
<h2>Kmom05: Lagra innehåll i databasen</h2>

<p>Det blir en del moduler i Anax, hur känns det?
Det känns bra, modulerna har verkligen börjat föröka sig nu, men det känns naturligt att skapa en modul för varje sak, det är som att programmera i C# eller andra programmeringsspråk, man skapar ett objekt och man använder det objektet för specifika saker. För mig känns det naturligt då min bakgrund ligger i objektorienterad programmering, och modulerna fungerar lite som objekt.</p>
 
<p>Berätta hur du tänkt runt strukturen av klasser och sidkontrollers
Till att börja med så använde jag CDatabase för att hantera databasen då den modulen redan fungerade som jag ville, och jag valde även att återanvända CUser då även den modulen fungerade som den skulle. Jag valde att lägga till de tre modulerna från guiden:</p>
 
<p>CContent, som används för att hantera skapande, raderande och uppdaterande av innehåll i databasen. Den har även funktioner för att hämta information utifrån slugs eller url. Den här modulen använder jag i princip i alla sidkontrollers som ska hantera informationen som ligger i databasen.</p>
 
<p>CPage och CBlog, används för att hämta en sida eller ett bloginlägg ur databasen, dom är arv utav CContent, då dom i princip gör exakt samma sak som CContent. Jag ville ha dom som egna moduler för att göra dom mer framtidssäkra, då jag vid senare tillfälle kan lägga till funktionalitet för dom specifika klasserna istället för att göra CContent större och större. Då det är objektorienterad php vi använder så kändes det även lämpligt att faktiskt använda arv.</p>
 
<p>Det finns även en modul som heter CTextFilter, detta är en modul som används för att kunna filtrera texten beroende på ifall man vill kunna använda markdown etc. Jag valde att flytta denna funktionalitet från webroot mappen till en modul, då jag kanske vill kunna återanvända detta filter till nått annat i framtiden. Modulerna handlar ju om att bygga en back-end som går att återanvända.</p>
 
<p>På sidkontroller-sidan av det hela så har jag gjort egna sidkontrollers för page (hemsidor) och blog (bloginlägg) men även sidkontrollers för att skapa, ta bort, editera och återställa saker i databasen. Alla dessa sidkontrollers använder sig av funktionalitet som finns CContent modulen. Jag är väldigt nöjd över hur det blev, och gjorde en modifierad variant av sidan till min egna hemsida, och det var väldigt enkelt att återanvända funktionalitet till något som är helt annorlunda.</p>
 
<p>Börjar du få känsla för att strukturera kod i moduler och klasser/Snart är grunderna klara, är det nått du saknar?
Det börjar kännas riktigt naturligt nu att skapa en modul och använda sig av klasser, det börjar kännas som att programmera med ett vanligt objektorienterat programmeringsspråk. När jag började med anax så hade jag ingen aning om vad jag skulle göra, men nu känns det naturligt. Det som saknas är hur man hanterar bilder, en klass för att skapa enkla bildalbum och kanske något för att kunna ladda upp bilder via en sidkontroller, men det kanske kommer i framtiden.</p>
EOD;

 
// Finally, leave it all to the rendering phase of en4fil.
include(EN4FIL_THEME_PATH);
?>
