<?php
/*
 * @author Caspar Crop && Robert-John van Doesburg
 * @klas ICT M1 E1
 * @projectGroup SSJ
 */
//prepare the database collection
$dbh = connectToDatabase();
$_SESSION['service_id'] = $_GET['id'];

$selectsql="SELECT servicename, pph, avgcost FROM services where service_id=:id";
$sth=$dbh->prepare($selectsql);
$sth->bindParam(":id", $_SESSION['service_id']);
$sth->execute();
$result= $sth->fetch(PDO::FETCH_ASSOC);

$dienst=$result['servicename'];
$pph=$result['pph'];
$avgcost=$result['avgcost'];

//if the user submitted the form, the following is done:
if (isset($_POST['vraagaan'])) 
{
//put all inputs in variables    

    $name = $_POST['name'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $zipcode = $_POST['zipcode'];
    $residence = $_POST['residence'];
    $telephone = $_POST['telephone'];
    $mobile = $_POST['mobile'];
    $date = $_POST['date'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];
    $description = $_POST['description'];
    $location = $_POST['location'];
    
    $email_regex = "^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$";
    $zipcode_regex = '(^[1-9]{1}[0-9]{3}?[A-Z]{2}$)';
    $telephone_regex = '/^(((0)[1-9]{2}[0-9][-]?[1-9][0-9]{5})|((\\+31|0|0031)[1-9][0-9][-]?[1-9][0-9]{6}))$/';
    $mobile_regex = '(^06-[0-9]{8}$)';
    $errors = array();
    
    if($name == '')
    {
    	$errors[] = 'U moet een naam opgeven.';
    }
    if($email == '' || !validEmail($email))
    {
    	$errors[] = 'U moet een geldig e-mail adres opgeven.';
    }    
    if($address == '')
    {
    	$errors[] = 'U moet een adres opgeven.';
    }    
    if($zipcode == '')
    {
    	$errors[] = 'U moet een postcode opgeven.';
    }
    else if(!preg_match($zipcode_regex, $zipcode))
    {
    	$errors[] = 'U moet een geldige Nederlandse postcode opgeven';
    }
    if($residence == '')
    {
    	$errors[] = 'U moet een woonplaats opgeven.';
    }    
    if($telephone == '' && $mobile == '')
    {
    	$errors[] = 'U moet ten minsten een telefoon- of mobiel nummer opgeven.';
    }
    else if($mobile != '' && !preg_match($mobile_regex, $mobile))
    {
    	$errors[] = 'U moet een geldig mobiel nummer opgeven.';
    }
    else if($telephone != '' && !preg_match($telephone_regex, $telephone))
    {
    	$errors[] = 'U moet een geldig telefoon nummer opgeven.';
    }
    if($date == '')
    {
    	$errors[] = 'U moet een datum opgeven.';
    }    
    if($start_time == '')
    {
    	$errors[] = 'U moet een start tijd opgeven.';
    }    
    if($end_time == '')
    {
    	$errors[] = 'U moet een eind tijd opgeven.';
    }     
    if($description == '')
    {
    	$errors[] = 'U moet een beschrijving opgeven.';
    }    
    if($location == '')
    {
    	$errors[] = 'U moet een locatie opgeven.';
    }   
    if(count($errors) == 0)
    {
    	
    	$query = "
    		INSERT INTO `dienst_aanvragen`
    			(`naam`, `email`, `adres`, `postcode`, `woonplaats`, `telefoon`, `mobiel`, `datum`, `start_tijd`, `eind_tijd`, `dienst_id`, `status`,`beschrijving`,`locatie`)
    		VALUES
    			(:naam, :email, :adres, :postcode, :woonplaats, :telefoon, :mobiel, STR_TO_DATE(:datum, '%d-%m-%Y'), :begin_tijd, :eind_tijd, :dienst_id, 'aangevraagd', :beschrijving, :locatie)
    	";
    	$sth = $dbh->prepare($query);
    	$sth->bindParam(":naam", $name);
    	$sth->bindParam(":email", $email);
    	$sth->bindParam(":adres", $address);
    	$sth->bindParam(":postcode", $zipcode);
    	$sth->bindParam(":woonplaats", $residence);
    	$sth->bindParam(":telefoon", $telephone);
    	$sth->bindParam(":mobiel", $mobile);
    	$sth->bindParam(":datum", $date);
    	$sth->bindParam(":begin_tijd", $start_time);
    	$sth->bindParam(":eind_tijd", $end_time);
    	$sth->bindParam(":dienst_id", $_GET['id']);
    	$sth->bindParam(":beschrijving", $description);
    	$sth->bindParam(":locatie", $location);
    	
    	if(!$sth->execute())
    	{
    		$errors[] = 'Er is iets mis gegaan met het versturen van de aanvraag.';
    		//var_dump($sth->errorInfo());exit;
    	}
    	else {
    		$to  = $email;
			
			// subject
			$subject = 'Aanvraag dienst - ' . $dienst;
			
			// message
			$message = '
				Beste ' . $name . ', <br /><br />
				U heeft de volgende dienst aangevraagd: <strong>' . $dienst . '</strong>.<br />
				U krijgt een mail wanneer de aanvraag is goedgekeurd.<br /><br />
				Met vriendelijke groet,<br />
				' . WEBSITE_NAAM . '
			';
			
			// To send HTML mail, the Content-type header must be set
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
			
			// Additional headers
			$headers .= 'To: ' . $name . ' <' . $email . '>' . "\r\n";
			$headers .= 'From: ' . WEBSITE_NAAM . ' <' . EMAIL_AFZENDER . '>' . "\r\n";
			
			// Mail it
			if(mail($to, $subject, $message, $headers))
			{
				
				header('location: /dienstaanvraag/' . $_GET['id'] . '/gelukt');
			}
			
			$errors[] = 'Er is iets mis gegaan met het versturen van de aanvraag.';
    	}
    }
}?>
<?php if(isset($errors) && count($errors) > 0):?>
	<div class="message_error">
		<?php foreach($errors as $message):?>
			<p><?php echo $message;?></p>
		<?php endforeach;?>
	</div><br />
<?php endif;?>
<?php if(!isset($_GET['status'])):?>
<form action="" method="POST">
	<table style="width: 100%;">
		<tr>
			<td>Naam:</td>
			<td><input type="text" name="name" value="<?php echo isset($name)?$name:''?>" /></td>
		</tr>
		<tr>
			<td>E-mail</td>
			<td><input type="text" name="email" value="<?php echo isset($email)?$email:''?>" /></td>
		</tr>
		<tr>
			<td>Adres</td>
			<td><input type="text" name="address" value="<?php echo isset($address)?$address:''?>" /></td>
		</tr>
		<tr>
			<td>Postcode</td>
			<td><input type="text" name="zipcode" maxlength="6" value="<?php echo isset($zipcode)?$zipcode:''?>" />&nbsp;<small>(1234AA)</small></td>
		</tr>
		<tr>
			<td>Woonplaats</td>
			<td><input type="text" name="residence" value="<?php echo isset($residence)?$residence:''?>" /></td>
		</tr>
		<tr>
			<td>Telefoonnummer</td>
			<td><input type="text" name="telephone" value="<?php echo isset($telephone)?$telephone:''?>" />&nbsp;<small>(050-1234567)</small></td>
		</tr>
		<tr>
			<td>Mobiel</td>
			<td><input type="text" name="mobile" value="<?php echo isset($mobile)?$mobile:''?>" />&nbsp;<small>(06-12345678)</small></td>
		</tr>
		<tr>
			<td>Datum</td>
			<td><input type="text" name="date" value="<?php echo isset($date)?$date:date('d-m-Y');?>" class="datepicker" /></td>
		</tr>
		<tr>
			<td>Begintijd en eindtijd</td>
			<td>
				<select name="start_time">
					<?php for($i = 0; $i <= 47; $i += 1):?>
						<?php $half = ($i % 2 != 0)?'30':'00';?>
						<?php $hour = floor($i / 2);?>
						<?php $hour = date('H', mktime($hour,0,0, 1, 1, 1970));?>
						<option <?php echo isset($start_time) && ($hour . ':' . $half == $start_time) ? 'selected="selected"':'';?> value="<?php echo $hour . ':' . $half; ?>"><?php echo $hour . ':' . $half; ?></option>
					<?php endfor;?>
				</select>
				<select name="end_time">
					<?php for($i = 0; $i <= 47; $i += 1):?>
						<?php $half = ($i % 2 != 0)?'30':'00';?>
						<?php $hour = floor($i / 2);?>
						<?php $hour = date('H', mktime($hour,0,0, 1, 1, 1970));?>
						<option <?php echo isset($end_time) && ($hour . ':' . $half == $end_time) ? 'selected="selected"':'';?> value="<?php echo $hour . ':' . $half; ?>"><?php echo $hour . ':' . $half; ?></option>
					<?php endfor;?>
				</select>
			</td>
		</tr>
		<tr>
			<td>Dienst:</td>
			<td><strong><?php echo $dienst;?></strong></td>
		</tr>
		<tr>
			<td>Locatie</td>
			<td><input type="text" name="location" value="<?php echo isset($location)?$location:''?>" /></td>
		</tr>
		<tr>
			<td>Beschrijving:</td>
		</tr>
		<tr>
			<td colspan="2">
				<textarea class="no-editor" name="description"><?php echo isset($description)?$description:''?></textarea>
			</td>
		</tr>
		<tr>
			<td><input type="hidden" name="service" value=""></td>
			<td></td>
		</tr>
		<tr>
			<td><input type="submit" name="vraagaan" value="Vraag aan!" /></td>
			<td></td>
		</tr>
		<tr>
			<td colspan="2"><small>Alle velden zijn verplicht<sup>1</sup></small></td>
		</tr>
		<tr>
			<td colspan="2"><small><sup>1</sup>U hoeft maar 1 nummer op te geven, telefoon- of mobiel nummer</small></td>
		</tr>
	</table>
	<input type="hidden" class="date_today" value="<?php echo date('m/d/Y');?>" />
</form>
<?php else:?>
	<?php if($_GET['status'] == 'gelukt'):?>
		<div class="message_success">
			<p>De aanvraag is gelukt. U ontvangt een mail ter bevestiging met de ingevulde gegevens</p>
			<p>Zodra de beheerder de aanvraag heeft goedgekeurd zult u nog een bericht ontvangen</p>
		</div>
	<?php endif;?>
<?php endif;?>