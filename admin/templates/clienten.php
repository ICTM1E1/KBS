<?php
/*
 * @author Jelle Kapitein
 * @klas ICT M1 E1
 * @projectGroup SSJ
 */

$dbh = connectToDatabase();
$style = "";
$statusText = "";

if(isset($_POST['search']) && !empty($_POST['search'])) {
  $search = "%".$_POST['search']."%";
  $query = "
	SELECT `users`.`id`, `users`.`username`, `user_data`.`naam`, `user_data`.`email` 
	FROM `users` 
	LEFT JOIN `user_data` 
		ON `users`.`id` = `user_data`.`user_id` 
	WHERE `users`.`admin` = 0
	AND `users`.`username` LIKE :search OR `user_data`.`email` LIKE :search
	OR `user_data`.`naam` LIKE :search
  ";
  $sth = $dbh->prepare($query);
  $sth->bindParam(":search", $search);
  $sth->execute();
  
  $res = $sth->fetchAll(PDO::FETCH_ASSOC);
  
} else {
	$query = "
		SELECT `users`.`id`, `users`.`username`, `user_data`.`naam`, `user_data`.`email` 
		FROM `users` 
		LEFT JOIN `user_data` 
			ON `users`.`id` = `user_data`.`user_id` 
		WHERE `users`.`admin` = 0
		LIMIT 0,30
	";
    $sth = $dbh->query($query);
    $sth->execute();

    $res = $sth->fetchAll(PDO::FETCH_ASSOC);
}

if (isset($_POST['option']) && isset($_POST['id'])) {
    $id = $_POST['id']; // Haal de ID array op
    $id = implode(',', $_POST['id']); // Zet de array om in een string, uit
    // elkaar gehouden door ,
    $id = mysql_real_escape_string($id); // Maak de string veilig voor de
    // database
    
    if ($_POST['option'] == "Verwijderen") { // Als er op de verwijder knop is gedrukt
	$sth = $dbh->prepare("DELETE FROM users WHERE ID IN(" . $id . ")"); // Verwijder het artikel
	$result1 = $sth->execute();
	
	$sth = $dbh->prepare("DELETE FROM user_data WHERE user_id IN(".$id.")");
	$result2 = $sth->execute();
	
	if ($result1 == true && $result2 == true) {
	    $style = 'message_success';
	    $statusText = "Gebruiker(s) succesvol verwijderd.";
	} else {
	    $style = 'message_error';
	    $statusText = "Er is een fout opgetreden tijdens het verwijderen van de gebruiker(s), de gebruiker(s) is niet verwijderd!";
	}
    } elseif($_POST['option'] == "Wachtwoord resetten") {
	$IDs = $_POST['id[]'];
	
	foreach($IDs as $row) {
	    $pw = "";
	    
	    $sth = $dbh->prepare("SELECT email,naam FROM user_data WHERE user_id=:id");
	    $sth->bindParam(":id", $row);
	    $sth->execute();
	    $res = $sth->fetchAll(PDO::FETCH_ASSOC);
	    $email = $res[0]['email'];
	    $naam = $res[0]['naam'];
	    
	    $pass = generatePassword();
	    
	    $to = $email;
	    $subject = "Uw wachtwoord is gereset";

	    $message = "
		Geachte heer/mevrouw,<br/><br/>
		De beheerder van de website ".SERVERPATH." heeft uw wachtwoord zojuist gereset.<br/><br/>
		Uw nieuwe wachtwoord is: ".$pass."<br/>
		Inloggen is mogelijk via de website.<br/><br/>
		Berg dit wachtwoord op een veilige plek op.<br/><br/>
		Met vriendelijke groet,<br/>
		".WEBSITE_NAAM."
	    ";

	    $headers  = 'MIME-Version: 1.0' . "\r\n";
	    $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
	    $headers .= 'To: ' . $naam . ' <' . $email . '>' . "\r\n";
	    $headers .= 'From: ' . WEBSITE_NAAM . ' <' . EMAIL_AFZENDER . '>' . "\r\n";

	    if(mail($to, $subject, $message, $headers)) {
		$pw = hash("sha256", $pass);
		$sth = $dbh->prepare("UPDATE users SET password=:pw WHERE id=:id");
		$sth->bindParam(":pw", $pw);
		$sth->bindParam(":id", $row);
		$sth->execute();
	    } else{
		$style = "message_error";
		$statusText = "Resetten van wachtwoorden ging mis op gebruiker ".$naam;
	    }
	}
    }
}?>

<div class="<?php echo $style; ?>">
    <p><?php echo $statusText; ?></p>
</div>

<form action="" method="post">
    <input type="button" onclick="window.location = '/admin/clienten/nieuw'" value="Nieuw"/>
    <input type="submit" name="option" value="Verwijderen"/>
    <input type="submit" name="option" value="Wachtwoord resetten"/>
    <br/><br/>
    <input type="text" placeholder="Zoeken..." name="search"/>
    <br/><br/>
    <table>
	<tr>
	    <th class="center"><input type="checkbox" id="checkall" value=""/></th>
	    <th>Gebruikersnaam</th>
	    <th>Volledige naam</th>
	    <th>E-mail adres</th>
	    <th>Bekijk berichten</th>
	    <th>Verstuur bericht</th>
	</tr>
	
    <?php
    foreach($res as $row) {
	$id = $row['id'];
	$unread = 0;
	
	$sth = $dbh->prepare("SELECT id FROM berichten WHERE afzender=:id AND gelezen=0");
	$sth->bindParam(":id", $id);
	$sth->execute();
	if($sth->rowCount()) {
	    $unread++;
	}
    ?>
	<tr>
	    <td class="center"><input type="checkbox" value="<?php echo($row['id'])?>" name="id[]"/></td>
	    <td><?php echo("<a href=/admin/clienten/bewerk/".$id.">".$row['username'])."</a>" ?></td>
	    <td><?php echo($row['naam']); ?></td>
	    <td><?php echo($row['email']); ?></td>
	    <td><a href="/admin/berichten/<?php echo($row['id']);?>">Berichten</a> <?php echo($unread > 0 ? "<b>(".$unread.")</b>" : ""); ?> </td>
	    <td><?php echo("<a href=/admin/berichten/nieuw/".$row['username'].">Maak nieuw bericht</a>"); ?>
	</tr>
    
    <?php
    }
    ?>
    </table>
</form>