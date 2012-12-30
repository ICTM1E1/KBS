<?php
/*
 * @author Caspar Crop
 * @klas ICT M1 E1
 * @projectGroup SSJ
 */
//db connection
$dbh = connectToDatabase();

//checking page number
if (isset($_GET["page"])) {
    $page = $_GET["page"];
} else {
    $page = 1;
};
//display 20 items per page
$start_from = ($page - 1) * 20;
//sql statement  
$sql = (" SELECT servicename, pph, avgcost FROM `services` WHERE pph IS NOT NULL ORDER BY servicename LIMIT $start_from, 20 ");
//execution and result of query
$result = selectquery($sql, $dbh);
?>
<!-- Explaination of rates -->

    <div id="ratewarning">
        <p>De prijzen die hier staan zijn geschatte prijzen voor gesprekken of diensten.
            <br/>Ook het uurtarief wordt hierbij weergeven</p>
	<hr>
    </div>
        <!--Table-->
        <table>
            <!--Tablehead-->
            <thead>
                <tr>
                    <th class="thrate">Dienst</th>
                    <th class="thrate">Uurtarief</th>
                    <th class="thrate">Geschatte prijs</th>
                </tr>
            </thead>
            <!--Tablebody-->
            <tbody>
		<?php
		//displaying all the values
		foreach ($result as $row) {
		    ?>
    		<tr>
    		    <!--Display servicename -->
    		    <td class="ratetablecollumn"><?php echo($row["servicename"]) ?></td>
    		    <!--Display price per hour -->
    		    <td class="ratetablecollumn"><?php echo('€ '.$row["pph"]) ?></td>
    		    <!--Display average cost-->
    		    <td class="ratetablecollumn"><?php echo('€ '.$row["avgcost"]) ?></td>
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
if($total_pages>1){
    //$i stands for the page number and starts at one
    for ($i = 1; $i <= $total_pages; $i++) {
	//$i (page number) implemented in link below table
	echo "<a href='/tarieven/" . $i . "'>" . $i . "</a> ";
    }
}
?>