<?php
/*
 * @author Jelle Kapitein
 * @klas ICT M1 E1
 * @projectGroup SSJ
 */

$dbh = connectToDatabase();

if(isset($_POST['search']) && !empty($_POST['search'])) {
  $search = "%".$_POST['search']."%";
  $sth = $dbh->prepare("SELECT ID,username,name,email FROM clients WHERE username LIKE :search OR email LIKE :search");
  $sth->bindParam(":search", $search);
  $sth->execute();
  
  $res = $sth->fetchAll(PDO::FETCH_ASSOC);
  
} else {
    $sth = $dbh->query("SELECT ID,username,name,email FROM clients LIMIT 0,30");
    $sth->execute();

    $res = $sth->fetchAll(PDO::FETCH_ASSOC);
}

?>

<form action="" method="post">
    <input type="button" onclick="window.location = '/admin/clienten/nieuw'" value="Nieuw"/>
    <input type="button" onclick="window.location = '/admin/clienten/verwijder'" value="Verwijderen"/>
    <input type="button" onclick="window.location = '/admin/clienten/pwreset'" value="Wachtwoord resetten"/>
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
	    <td class="center"><input type="checkbox" name="id[]" value="<?php echo($row['ID'])?>"/></td>
	    <td><?php echo("<a href=/admin/clienten/bewerk/".$row['ID'].">".$row['username'])."</a>" ?></td>
	    <td><?php echo($row['name']); ?></td>
	    <td><?php echo($row['email']); ?></td>
	    <td> Link (Totaal) (Ongelezen) </td>
	    <td><?php echo("<a href=/admin/clienten/sendmessage/".$row['ID'].">(plaatje)</a>"); ?>
	</tr>
    
    <?php
    }
    ?>
    </table>
</form>