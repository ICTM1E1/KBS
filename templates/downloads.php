<?php
/*
 * @author Erik de Vries
 * @klas ICT M1 E1
 * @projectGroup SSJ
 */
//haalt de page op voor de url
if (isset($_GET["page"])) {
    $page = $_GET["page"];
} else {
    $page = 1;
};
//worden 10 results weergegeven per pagina.
$start_from = ($page - 1) * 10;
//db
$dbh = connectToDatabase();
$sql1 = "SELECT * FROM downloads ORDER BY ID DESC LIMIT $start_from, 10";
$result1 = selectquery($sql1, $dbh)
?>
<div class="page">
    <div class="pagination">
	<?php
//db
	$sql2 = "SELECT * FROM downloads";
	$result2 = selectquery($sql2, $dbh);
//het aantal records, aantal word berekent door $result bij elkaar optetellen
	$total_records = count($result2);
//het aantal pages, aantal pages wordt berekend door het aantal records delen door 10
	$total_pages = ceil($total_records / 10);
//$1 als er niet genoeg bestanden zijn voor 2 pagina's, is de pagination niet zichtbaar
	if ($total_pages > 1) {
//$1 staat voor de pagina nummer, begint op 1
	    for ($i = 1; $i <= $total_pages; $i++) {
// zorgt ervoor dat de paginanummer van de pagina waar hij nu op zit niet wordt weergegeven
		if ($i != $page) {
//$1 (de pagina nummer) komt achter de url de staan en wordt weergegeven als $1 onder de tabel
		    echo "<a href='/downloads/" . $i . "'>" . $i . "</a> ";
		}
	    }
	}
	?>
    </div>
    <!-- geeft weer op welke pagina je zit -->
    <p> <strong> Pagina: <?php echo $i = $page ?> </strong> </p>
</div>
<div  id="downloads">
    <table>
	<tr id="head">    
	    <th> Bestanden </th>    
	    <th> Grootte </th>
	    <th> Download </th>
	</tr>
	<?php foreach ($result1 as $row) {
	    ?>
    	<tr id="row">
    	    <!-- Laat het bestand naam zien. -->
    	    <td> <?php echo ($row["file"]); ?> </td>
    	    <!-- Laat de size van het bestand zien in kb. -->
    	    <td> <?php echo ($row["size"]); ?> kb </td>
    	    <!-- Met deze functie kan je bestanden downloaden die geupload zijn. -->
    	    <td> <a href=http://kbs.nl/uploads/<?php echo rawurlencode($row["file"]) ?> >Download</a> </td>
    	</tr>    

	<?php } ?> 
    </table>
</div>
