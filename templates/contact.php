<?php
/*
 * @author Maarten Engels
 * @klas ICT M1 E1
 * @projectGroup SSJ
 */

$dbh = connectToDatabase();

//Het zelfafhandelende gedeelte, waarbij het formulier wordt getoond als nog niet op de verstuur-knop is gedrukt
if (isset($_POST["verstuur"])) {
    //De attributen die worden opgeslagen vanuit het contactformulier, zodat in de e-mail die gestuurd wordt staat van wie het afkomstig is, wat het onderwerp is en wat de vraag is
    $naam = $_POST['naam'];
    $email = $_POST['email'];
    $onderwerp = $_POST['onderwerp'];
    $vraag = $_POST['vraag'];

    //Eigenaar's email, dus gerelateerde van Caspar (nu voor testen nog mijn e-mail
    $to = EMAIL_KLANT;
    $subject = 'Vraag van bezoeker ' . $naam;

    //Hier de info over de mail en de vraag
    $message = 'Van: ' . $naam . "\n";
    $message .= 'E-mail: ' . $email . "\n";
    $message .= 'Onderwerp: ' . $onderwerp;
    $message .= 'Vraag: <br>' . $vraag;

    //$headers = 'From: '.$email."\r\n";
    $headers = 'From: ' . EMAIL_KLANT . '\r\n';
    $headers .= 'Reply-To: ' . $email . "\r\n";


    //Hier de info over vanwaar de e-mail komt
    $headers = 'From: ' . EMAIL_KLANT . '\r\n';
    $headers .= 'Reply-To: ' . $email . "\r\n";

    //De functie waarbij de mail verstuurd wordt, of niet verstuurd als de gegevens niet ingevuld of niet correct ingevuld zijn.
    $mail_status = mail($to, $subject, $message, $headers);
    if (!$mail_status) {
	echo '<div class="message_error"><p>Helaas, het versturen van de mail is mislukt</p></div>';
    } else {
	echo '<div class="message_success"><p>Geslaagd, de mail is verstuurd</p></div>';
    }


/*
 * @author Caspar Crop
 * @klas ICT M1 E1
 * @projectGroup SSJ
 */    
    
    //Als klant ingelogd is, dan worden automatisch e-mail en naam ingevoerd.

    if (isset($_SESSION['clientid'])) {
	
	//sql query for retreiving the user's data
	$userinfosql = "SELECT * 
		    FROM user_data
		    WHERE user_id =:id";
	//preparing the sql statement
	$sth = $dbh->prepare($userinfosql);
	//binding the variables
	$sth->bindParam(":id", $_SESSION['clientid']);
	//executing the query
	$sth->execute();
	//retreiving the data collected by the query
	$res = $sth->fetch(PDO::FETCH_ASSOC);
	?>
	<!--Contact-form-->
	<form method="post">
	    <table>
		<tr>
		    <td>Naam</td>
		    <td><input type="text" name="naam" value="<?php echo $res['naam']; ?>"></td>
		</tr>
		<tr>
		    <td>E-mailadres</td>
		    <td><input type="text" name="email" value="<?php echo $res['email']; ?>"></td>
		</tr>
		<tr>
		    <td>Onderwerp</td>
		    <td><input type="text" name="onderwerp"></td>
		</tr>
		<tr>
		    <td>Vraag</td>
		    <td>&nbsp;</td>
		</tr>
		<tr>
		    <td colspan="2"><textarea name="vraag"></textarea></td>
		</tr>
		<tr>
		    <td>&nbsp;</td>
		    <td align="right" >
			<input type="submit" name="verstuur" value="Versturen" class="submit"/>
		    </td>
		</tr>
	    </table>
	</form>

<?php
/*
 * @author Maarten Engels
 * @klas ICT M1 E1
 * @projectGroup SSJ
 */
?>

    <?php } else { ?>
	<!-- Het contactformulier waarbij je de Naam, het E-mailadres, het Onderwerp en de Vraag in kan vullen en op Versturen kan drukken. -->
	<form method="post">
	    <table>
		<tr>
		    <td>Naam</td>
		    <td><input type="text" name="naam"></td>
		</tr>
		<tr>
		    <td>E-mailadres</td>
		    <td><input type="text" name="email"></td>
		</tr>
		<tr>
		    <td>Onderwerp</td>
		    <td><input type="text" name="onderwerp"></td>
		</tr>
		<tr>
		    <td>Vraag</td>
		    <td>&nbsp;</td>
		</tr>
		<tr>
		    <td colspan="2"><textarea name="vraag"></textarea></td>
		</tr>
		<tr>
		    <td>&nbsp;</td>
		    <td align="right" >
			<input type="submit" name="verstuur" value="Versturen" class="submit"/>
		    </td>
		</tr>
	    </table>
	</form>
    <?php } }?>
