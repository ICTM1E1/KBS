<?php

if(isset($_GET['option'])) {
    $dbh = connectToDatabase();
    
    if($_GET['option'] == "remove") { 
	$IDs = $_POST['id[]'];
	$IDs = implode(",", $IDs);
	$IDs = mysql_real_escape_string($IDs);
	
	$sth = $dbh->query("DELETE FROM clients WHERE ID IN (".$IDs.")");
	$result = $sth->execute();
	    
	if(!$result) {
	    // todo after merge
	}
    } elseif($_GET['option'] == "pwreset") {
	$IDs = $_POST['id[]'];
	
	foreach($IDs as $row) {
	    $pw = "";
	    
	    $sth = $dbh->prepare("SELECT email FROM clients WHERE ID=:id");
	    $sth->bindParam(":id", $row);
	    $sth->execute();
	    $res = $sth->fetchAll(PDO::FETCH_ASSOC);
	    $email = $res[0];
	    
	    mail($email,"Uw wachtwoord is gereset","Blah");
	    
	    $sth = $dbh->prepare("UPDATE clients SET password=:pw WHERE ID=:id");
	    $sth->bindParam(":pw", $pw);
	    $sth->bindParam(":id", $row);
	    //$sth->execute();
	}
    }
}

if($_GET['option'] == "edit") { 
    $id = $_GET['id'];
}
?>