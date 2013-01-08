<?php

if(isset($_POST['submit'])) {
    $dbh = connectToDatabase();
    
    echo($_GET['id']);
    echo($_POST['user']);
    
    $id = $_GET['id'];
    $user = $_POST['user'];
    $name = $_POST['name'];
    $email = $_POST['email'];
       
    if(empty($user) or empty($name) or empty($email)) {
	echo("Er mist een vereist veld. Klik <a href=\"/admin/bewerkclienten/bewerk/".$id."\">hier</a> om terug te gaan.");
	exit;
    }
    
    $sth = $dbh->prepare("UPDATE clients SET username=:user,name=:name,email=:email WHERE ID=:id");
    $sth->bindParam(":user", $user);
    $sth->bindParam(":name", $name);
    $sth->bindParam(":email", $email);
    $sth->bindParam(":id", $id);
    $res = $sth->execute();
    
}

if(isset($_GET['option'])) {
    $dbh = connectToDatabase();
    
    if($_GET['option'] == "verwijder") { 
	$IDs = $_GET['id[]'];
	$IDs = implode(",", $IDs);
	$IDs = mysql_real_escape_string($IDs);
	
	$sth = $dbh->query("DELETE FROM clients WHERE ID IN (".$IDs.")");
	$result = $sth->execute();
	    
	if(!$result) {
	    // todo after merge
	}
    } elseif($_GET['option'] == "pwreset") {
	$IDs = $_GET['id[]'];
	
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

if($_GET['option'] == "bewerk") { 
    $id = $_GET['id'];
    
    $sth = $dbh->prepare("SELECT username, name, email FROM clients WHERE ID=:id ORDER BY name ASC");
    $sth->bindParam(":id", $id);
    $sth->execute();
    
    $res = $sth->fetchAll(PDO::FETCH_ASSOC);
    
    $name = $res[0]['name'];
    $username = $res[0]['username'];
    $email = $res[0]['email'];    
}

if($_GET['option'] == "nieuw") {
    $name = "";
    $username = "";
    $email = "";
}
?>

<form action="" method="post">
    <input name="id" type="hidden" value="<?echo($_GET['id']);?>"/>
    <table class="simple-table">
	<tr>
	    <td colspan="2">Naam:</td>
	    <td colspan="2"><input name="name" type="text" size="40" value="<?php echo($name);?>"/></td>
	</tr>
	<tr>
	    <td colspan="2">Gebruikersnaam:</td>
	    <td colspan="2"><input name="user" type="text" size="40" value="<?php echo($username);?>"/></td>
	</tr>
	<tr>
	    <td colspan="2">E-mail adres:</td>
	    <td colspan="2"><input name="email" type="text" size="40" value="<?php echo($email);?>"/></td>
	</tr>
	<tr>
	    <td><input type="submit" name="submit" value="Opslaan"/> <input type="button" name="cancel" value="Annuleren" onclick="window.location = '/admin/clienten/'"/></td>
	</tr>
    </table>
</form>