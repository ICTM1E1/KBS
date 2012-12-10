<?php
include DOCROOT . 'inc/mysql.inc.php';
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
$sth = $dbh->prepare("SELECT * FROM downloads LIMIT $start_from, 10");
$sth->execute();
$result = $sth->fetchAll(PDO::FETCH_ASSOC);
?>
<table border="1">
    <tr>    
        <th> Bestanden </th>    
        <th> Grootte </th>
        <th> Download </th>
    </tr>
    <?php foreach ($result as $row) {
	?>
        <tr>
    	<!-- Laat het bestand naam zien. -->
    	<td> <?php echo ($row["file"]); ?> </td>
    	<!-- Laat de size van het bestand zien in kb. -->
    	<td> <?php echo ($row["size"]); ?> kb </td>
    	<!-- Met deze functie kan je bestanden downloaden die geupload zijn. -->
    	<td> <a href=http://kbs.nl/uploads/<?php echo rawurlencode($row["file"]) ?> >Download</a> </td>
        </tr>    

    <?php } ?> 
</table>
<?php
//db
$sth = $dbh->prepare("SELECT * FROM downloads");
$sth->execute();
$result = $sth->fetchall(PDO::FETCH_ASSOC);
//het aantal records, aantal word berekent door $result bij elkaar optetellen
$total_records = count($result);
//het aantal pages, aantal pages wordt berekend door het aantal records delen door 10
$total_pages = ceil($total_records / 10);
//$1 staat voor de pagina nummer, begint op 1
for ($i = 1; $i <= $total_pages; $i++) {
//$1 (de pagina nummer) komt achter de url de staan en wordt weergegeven als $1 onder de tabel
    echo "<a href='/downloads/" . $i . "'>" . $i . "</a> ";
};
?>
