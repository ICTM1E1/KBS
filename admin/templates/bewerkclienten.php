<?php

if(isset($_POST['submit'])) {
    $dbh = connectToDatabase();
    
    echo($_POST['id']);
    echo($_POST['user']);
    
    $id = $_POST['id'];
    $user = $_POST['user'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $adres = $_POST['adres'];
    $postc = $_POST['postcode'];
    $woonp = $_POST['woonplaats'];
    $telnr = $_POST['telnr'];
    $mob   = $_POST['mobiel'];
       
    if(empty($user) or empty($name) or empty($email)) {
	echo("Er mist een vereist veld. Klik <a href=\"/admin/bewerkclienten/bewerk/".$id."\">hier</a> om terug te gaan.");
	exit;
    }
    
    if($id == -1) {
	$sth = $dbh->prepare("INSERT INTO users (username, password) VALUES(:user, :pass)");
	$sth->bindParam(":user", $user);
	$sth->bindParam(":pass", hash('sha256', 'test'));
	$sth->execute();
	
	$new_id = $dbh->lastInsertId();
	
	$sth = $dbh->prepare("INSERT INTO user_data (user_id,naam,adres,postcode,woonplaats,telefoon,mobiel,email) VALUES (:id,:naam,:adres,:postc,:woonpl,:tel,:mob,:email) ");
	$sth->bindParam(":id", $new_id);
	$sth->bindParam(":naam", $name);
	$sth->bindParam(":adres", $adres);
	$sth->bindParam(":postc", $postc);
	$sth->bindParam(":woonpl", $woonp);
	$sth->bindParam(":tel", $telnr);
	$sth->bindParam(":mob", $mob);
	$sth->bindParam(":email", $email);
    }else{
	$sth = $dbh->prepare("UPDATE users SET username=:user WHERE id=:id");
	$sth->bindParam(":user", $user);
	$sth->bindParam(":id", $id);

	$sth = $dbh->prepare("UPDATE user_data SET name=:name,email=:email,adres=:adres,postcode=:post,woonplaats=:woonp,telefoon=:telnr,mobiel=:mob WHERE user_id=:id");
	$sth->bindParam(":name", $name);
	$sth->bindParam(":email", $email);
	$sth->bindParam(":adres", $adres);
	$sth->bindParam(":post", $postc);
	$sth->bindParam(":woonp", $woonp);
	$sth->bindParam(":telnr", $telnr);
	$sth->bindParam(":mob", $mob);
	$sth->bindParam(":id", $id);
	$res = $sth->execute();
    }
}

if(isset($_GET['option'])) {
    $dbh = connectToDatabase();
    
    if($_GET['option'] == "verwijder") { 
	$IDs = $_GET['id[]'];
	$IDs = implode(",", $IDs);
	$IDs = mysql_real_escape_string($IDs);
	
	$sth = $dbh->query("DELETE FROM user_data WHERE ID IN (".$IDs.")");
	$result = $sth->execute();
	    
	if(!$result) {
	    // todo after merge
	}
    } elseif($_GET['option'] == "pwreset") {
	$IDs = $_GET['id[]'];
	
	foreach($IDs as $row) {
	    $pw = "";
	    
	    $sth = $dbh->prepare("SELECT email FROM user_data WHERE user_id=:id");
	    $sth->bindParam(":id", $row);
	    $sth->execute();
	    $res = $sth->fetchAll(PDO::FETCH_ASSOC);
	    $email = $res[0];
	    
	    mail($email,"Uw wachtwoord is gereset","Blah");
	    
	    $sth = $dbh->prepare("UPDATE users SET password=:pw WHERE id=:id");
	    $sth->bindParam(":pw", $pw);
	    $sth->bindParam(":id", $row);
	    //$sth->execute();
	}
    }
}

if($_GET['option'] == "bewerk") { 
    $id = $_GET['id'];
    
    $sth = $dbh->prepare("SELECT username, naam, email, adres, postcode, woonplaats, telefoon, mobiel FROM user_data UD JOIN users U on UD.user_id=U.id WHERE user_id=:id");
    $sth->bindParam(":id", $id);
    $sth->execute();
    
    $res = $sth->fetchAll(PDO::FETCH_ASSOC);
    
    $name = $res[0]['naam'];
    $username = $res[0]['username'];
    $email = $res[0]['email'];  
    $adres = $res[0]['adres'];
    $postcode = $res[0]['postcode'];
    $woonplaats = $res[0]['woonplaats'];
    $telefoon = $res[0]['telefoon'];
    $mobiel = $res[0]['mobiel'];
}

if($_GET['option'] == "nieuw") {
    $id = -1;
    $name = "";
    $username = "";
    $email = "";
    $adres = "";
    $postcode = "";
    $woonplaats = "";
    $telefoon = "";
    $mobiel = "";
}

if($username && $email) {
?>

<form action="" method="post">
    <input name="id" type="hidden" value="<?echo($id);?>"/>
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
	    <td colspan="2">Adres:</td>
	    <td colspan="2"><input name="adres" type="text" size="40" value="<?php echo($adres);?>"/></td>
	</tr>
	<tr>
	    <td colspan="2">Postcode:</td>
	    <td colspan="2"><input name="postcode" type="text" size="40" value="<?php echo($postcode);?>"/></td>
	</tr>
	<tr>
	    <td colspan="2">Woonplaats:</td>
	    <td colspan="2"><input name="woonplaats" type="text" size="40" value="<?php echo($woonplaats);?>"/></td>
	</tr>
	<tr>
	    <td colspan="2">Telefoon nummer:</td>
	    <td colspan="2"><input name="telnr" type="text" size="40" value="<?php echo($telefoon);?>"/></td>
	</tr>
	<tr>
	    <td colspan="2">Mobiel:</td>
	    <td colspan="2"><input name="mobiel" type="text" size="40" value="<?php echo($mobiel);?>"/></td>
	</tr>
	<tr>
	    <td><input type="submit" name="submit" value="Opslaan"/> <input type="button" name="cancel" value="Annuleren" onclick="window.location = '/admin/clienten/'"/></td>
	</tr>
    </table>
</form>

<?php
}?>