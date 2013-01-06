<?php
/*
 * @author Jelle Kapitein
 * @klas ICT M1 E1
 * @projectGroup SSJ
 */

$dbh = connectToDatabase();

if(isset($_POST['search']) && !empty($_POST['search'])) {
  $search = "%".$_POST['search']."%";
  $query = "
	SELECT `users`.`id`, `users`.`username`, `user_data`.`naam`, `user_data`.`email` 
	FROM `users` 
	LEFT JOIN `user_data` 
		ON `users`.`id` = `user_data`.`user_id` 
	WHERE `users`.`admin` = 0
	AND `users`.`username` LIKE :search OR `user_data`.`email` LIKE :search
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

?>

<form action="" method="post">
    <input type="button" onclick="window.location = '/admin/clients/new'" value="Nieuw"/>
    <input type="button" onclick="window.location = '/admin/clients/remove'" value="Verwijderen"/>
    <input type="button" onclick="window.location = '/admin/clients/pwreset'" value="Wachtwoord resetten"/>
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
    ?>
	<tr>
	    <td class="center"><input type="checkbox" name="id[]" value="<?php echo($row['id'])?>"/></td>
	    <td><?php echo("<a href=/admin/clients/edit/".$row['id'].">".$row['username'])."</a>" ?></td>
	    <td><?php echo($row['naam']); ?></td>
	    <td><?php echo($row['email']); ?></td>
	    <td> Link (Totaal) (Ongelezen) </td>
	    <td><?php echo("<a href=/admin/clients/sendmessage/".$row['id'].">(plaatje)</a>"); ?>
	</tr>
    
    <?php
    }
    ?>
    </table>
</form>