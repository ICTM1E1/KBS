<?php
/*
 * @author Caspar Crop
 * @klas ICT M1 E1
 * @projectGroup SSJ
 */

//connecting to the database
$dbh = connectToDatabase();
//checking page number
if (isset($_GET["page"])) {
    $page = $_GET["page"];
} else {
    $page = 1;
};
//display 20 items per page
$start_from = ($page - 1) * 20;
//the sql statement
$sql = (" SELECT service_id, servicename, pph, avgcost FROM `services` ORDER BY servicename LIMIT $start_from, 20");
//retrieving the query results
$result = selectquery($sql, $dbh);
?>
<div>
    <!--Displaying the table-->
    <table class="hover">

	<!--Displaying the tablehead-->
        <thead>
            <tr>
                <th>Dienst</th>
                <th>Uurtarief</th>
                <th>Geschatte prijs</th>
                <th>Wijzig tarieven</th>
            </tr>
        </thead>

        <!--Displaying the tablebody-->
        <tbody>
	    <?php
	    //displaying all the values
	    foreach ($result as $row) {
		?>
    	    <tr>
    		<!--displays the service name-->
    		<td><?php echo($row["servicename"]) ?></td>
    		<!--displays the price per hour-->
    		<td><?php echo($row["pph"]) ?></td>
    		<!--displays the average cost-->
    		<td><?php echo($row["avgcost"]) ?></td>
    		<!--displays the link to change the rates-->
    		<td><a class="button" href="<?php echo '/admin/wijzigtarief/' . $row['service_id'] ?>">Wijzig</a></td>
    	    </tr>
	    <?php } ?>
        </tbody>

    </table>
    <?php
//sql statement
    $sql2 = "SELECT * FROM services";
    $result2 = selectquery($sql2, $dbh);
//calculate total records
    $total_records = count($result2);
//calculate pages by dividing total records by 20
    $total_pages = ceil($total_records / 20);
//check if page total is larger than 1, if it is larger, pages will be put in.
    if ($total_pages > 1) {
	//$i stands for the page number and starts at one
	for ($i = 1; $i <= $total_pages; $i++) {
	    // zorgt ervoor dat de paginanummer van de pagina waar hij nu op zit niet wordt weergegeven
		if ($i != $page) {
		//$i (page number) implemented in link below table
		echo "<a href='/admin/admintarieven/" . $i . "'>" . $i . "</a> ";
	    }
	}
    }
    ?>
</div>