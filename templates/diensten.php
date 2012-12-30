<?php
/*
 * @author Richard van den Hoorn & Caspar Crop
 * @klas ICT M1 E1
 * @projectGroup SSJ
 */

//database connection
$dbh = connectToDatabase();

//if there is an ID in the URL, show the article related to the service and its link
if (isset($_GET['id'])) {
    //query for selecting the service ID servicename, servicetext and article ID
    //sql query
    $sth1 = $dbh->prepare("SELECT service_id, servicename, servicetext, pph, avgcost, article_id FROM services WHERE service_id=:id");
    //binding variable
    $sth1->bindParam(":id", $_GET['id']);
    //executing the query
    $sth1->execute();
    //retrieving resulted data
    $result = $sth1->fetchAll(PDO::FETCH_ASSOC);

    //for each service
    foreach ($result as $row) {
	//assign rows:
	$service_id = $row['service_id'];
	$servicename = $row['servicename'];
	$article_id = $row['article_id'];
	$pph = $row['pph'];
	$avgcost = $row['avgcost'];

	//query for selecting the affiliated article
	//sql query
	$sth = $dbh->prepare("SELECT * FROM article WHERE ID=:id");
	//binding variables
	$sth->bindParam(":id", $article_id);
	//executing the query
	$sth->execute();
	//retreiving resulted data
	$res = $sth->fetchAll(PDO::FETCH_ASSOC);

	//for each article
	foreach ($res as $row) {
	    //assign date format
	    $date = date("d-m-Y H:i:s", strtotime($row ['date_added']));
	    $datee = date("d-m-Y H:i:s", strtotime($row ['date_edited']));
	    ?>
	    <!--Display the article title-->
	    <div class="artikel" id="title">
	        <h2><?php echo $row['title'] ?></h2>
	    </div>
	    
	    <!--Display the article dates-->
	    <div class="artikel" id="dates">
	        <i>Aangemaakt: <?php echo $date ?></i>
		<?php if ($date != $datee) { ?>
		    <i><br />Laatst gewijzigd: <?php echo $datee ?></i>
		<?php } ?>
	    </div>
	    
	    <!--Display Article text-->
	    <div class="artikel" id="text">
		<?php
		echo $row['text'];
		?>
		<br>
	    </div>

	<?php } 
	    echo'<hr><br/>Prijs per uur voor deze dienst: € '.$pph.'	Gemiddelde prijs van deze dienst: € '.$avgcost.'<hr><br/>';?>
	<!--Display the link for asking for the service-->
	<div id="dalink">
	    <?php
	    
	    echo '<a href="/dienstaanvraag/' . $service_id . '">Vraag de dienst ' . $servicename . ' aan</a>';
	    ?> 
	</div>
	<br/>
	<?php
	}
	?>
	
    <?php
//if there is no ID in the URL:
} else {

    //sql query for selecting all published services
    $sql = "SELECT * FROM services WHERE Published=1";
    //retreive results from query
    $result = selectquery($sql, $dbh)
    ?>

    <table  class="diensten">
	<?php
	//display 2 service blocks per row.
	$i = 0;
	foreach ($result as $row) {  // Loop door SQL-resultaten
	    if ($i == 0) {
		echo("<tr>");
	    }
	    echo("<td class=cell style='cursor: pointer' onclick=window.location='diensten/" . $row['service_id'] . "' >");
	    echo("<div class=title><h2>" . $row['servicename'] . "</h2></div>");
	    echo("<br /><div class=discription>" . $row['servicetext'] . "</div>");
	    echo("</td>");
	    if ($i == 1) {
		echo("</tr>");
	    }
	    $i++;
	    if ($i == 2) {
		$i = 0;
	    }
	}
	?>
    </table>
<?php } ?>