<?php
/*
 * @author Caspar Crop
 * @klas ICT M1 E1
 * @projectGroup SSJ
 */

//databace connection
$dbh = connectToDatabase();
//checking if form is filled in and submitted
if (isset($_POST['Verander!'])) {
    if (isset($_POST['pph']) AND isset($_POST['avgcost'])) {
	//assigning values
	$ident = $_POST['id'];
	$priceph = $_POST['pph'];
	$average = $_POST['avgcost'];
	if(is_numeric($priceph)==false or is_numeric($average)==false){
	    echo '<div class="message_error"><p>U moet cijfers voor de prijzen gebruiken</p></div>';
	}
	elseif(($average>=0) and ($priceph>=0)){
	//updating the database
	//sql statement
	$sql = (" UPDATE services SET pph=$priceph, avgcost=$average WHERE service_id=$ident ");
	//executing the statement
	$sth = $dbh->prepare($sql);
	$sth->execute();
	header('Location:/admin/admintarieven');
    }
    else{echo '<div class="message_error"><p>U moet uw prijzen op 0 of hoger hebben</p></div>';}
    }
}

    //retrieving rates from service
    //retrieving ID
    $id = $_GET['id'];
    //sql statement
    $sql = ('SELECT pph, avgcost FROM services WHERE service_id=' . $id . ' LIMIT 1');
    //execution
    $sth = $dbh->prepare($sql);
    $sth->execute();
    //retrieving resulted data
    $row = $sth->fetch(PDO::FETCH_ASSOC);
    ?>
    <!--Form for input of price per hour and average price-->
    <form action="" method="POST">
        <input type="hidden" name="id" value="<?php echo($id); ?>">

        <label>Prijs per uur:</label>
        <br>
        <input type="text" name="pph" value="<?php echo($row['pph']) ?>" />
        <br/><br/>
        <label>Gemiddelde prijs:</label>
        <br>    
        <input type="text" name="avgcost" value="<?php echo($row['avgcost']) ?>" />
        <br/><br/>
        <input type="submit" value="Verander!" name="Verander!" />

    </form>