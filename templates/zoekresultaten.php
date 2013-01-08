<!--
Auteur: Maarten Engels
	s1058387
	ICTM1e
-->
<?php
/*
 * @author Maarten Engels
 * @klas ICT M1 E1
 * @projectGroup SSJ
 */
//Het woord dat ingevuld wordt in de zoekbalk wordt opgehaald en gebruikt als attribuut voor de SQL-querie.
if (isset($_POST['zoekwoord123']) && !empty($_POST['zoekwoord123'])) {
    $zoekwoord = mysql_real_escape_string($_POST["zoekwoord123"]);
    $_SESSION["zoekresultaat"] = $zoekwoord;
}

//Als er geen zoekwoord is ingevuld, maar wel op het zoekveld of op de zoekknop is geklikt, geeft hij hier aan dat er geen zoekwoord is ingevuld
if (empty($zoekwoord)) {
    echo "Geen zoekopdracht ingevuld, vul a.u.b. een zoekopdracht in.";
}

//Zelfafhandelend formulier waarbij er naar resultatenpagina 1 gaat als er nog geen paginanummer is opgegeven
if (isset($_GET["page"])) {
    $page = $_GET["page"];
} else {
    $page = 1;
};
if ($page > 1) {
    $zoekwoord = $_SESSION["zoekresultaat"];
}

$start_from = ($page - 1) * 4;

//Zelfafhandelend formulier waarbij de resultaten 
if (isset($zoekwoord) && !empty($zoekwoord)) {
//Het verbinden met de database wordt in dit stuk tekst gedaan, tevens wordt de SQL-querie hier uitgevoerd voor de resultaten.

    $dbh = connectToDatabase();
    $sql1 = " SELECT * 
        FROM article 
        WHERE title LIKE '%$zoekwoord%'
        OR text LIKE '%$zoekwoord%' 
	LIMIT $start_from, 4
      ";
    $result1 = selectquery($sql1, $dbh)
    ?>

    <?php
    if ($zoekwoord != "") {
	echo "U heeft gezocht op \"$zoekwoord\" .";
    } else {
	echo "U heeft geen zoekopdracht ingevoerd. probeer het opnieuw.";
    }
//De functie( foreach() ) om de zoekresultaten te laten zien voor het attribuut $zoekwoord, als het aantal resultaten groter is dan 0 wordt er geen foutmelding weergegeven
    if (count($result1) > 0) {
	foreach ($result1 as $row) {
	    ?>

	    <div class="zoekresultaat">
	        <h3><?php echo $row["title"]; ?></h3>
	        <p><?php echo strip_tags($row["text"]); ?></p>
	        <a href="/artikel/<?php echo $row["ID"]; ?>">Lees meer</a>
	    </div>

	    <?php
	}

	//Als er geen overeenkomende zoekresultaten gevonden zijn, komt deze foutmelding er te staan
    } else {
	echo 'Geen zoekresultaten gevonden, probeer iets anders.';
    }
    ?>
    <?php
    $sql2 = "SELECT * 
        FROM article 
        WHERE title LIKE '%$zoekwoord%'
        OR text LIKE '%$zoekwoord%' 
       ";
    $result2 = selectquery($sql2, $dbh);
    $total_records = count($result2);
    $total_pages = ceil($total_records / 4);

    for ($i = 1; $i <= $total_pages; $i++) {
	echo "<a href='/zoekresultaten/" . $i . "'>" . $i . "</a>&nbsp;";
    };
}
?>
