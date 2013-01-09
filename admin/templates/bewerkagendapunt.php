<?php 
/*
 * @author Robert-John van Doesburg
 * @klas ICT M1 E1
 * @projectGroup SSJ
 */

$db = connectToDatabase();
$requested = false;
$user_data = false;
$errors = array();

if(isset($_GET['requested']) && $_GET['requested'] == true) {
	$requested = true;
	
	$parameters = array(':id' => $_GET['id']);
	
	$sth = $db->prepare("
			SELECT `services`.`servicename`, `dienst_aanvragen`.*
			FROM `dienst_aanvragen`
			LEFT JOIN `services`
			ON `dienst_aanvragen`.`dienst_id` = `services`.`service_id`
			WHERE `id` = :id
		");
	$sth->execute($parameters);
	$user_data = $sth->fetch(PDO::FETCH_ASSOC);
	
	$date_explode = explode('-', $user_data['datum']);
	$user_data['datum'] = date('d-m-Y', mktime(0, 0, 0, $date_explode[1], $date_explode[2], $date_explode[0]));
	$user_data['start_tijd'] = date('H:i',strtotime($user_data['start_tijd']));
	$user_data['eind_tijd'] = date('H:i',strtotime($user_data['eind_tijd']));
}

if(isset($_POST['delete-agenda-point']))
{
	if($requested)
	{
		$parameters = array(':id' => $_GET['id']);
				
		$sth = $db->prepare("DELETE FROM `dienst_aanvragen` WHERE `id` = :id ");
		
		$result = $sth->execute($parameters);
		
		//mail($user_data['email'])
		
	}
	else {
		$sth = $db->prepare("DELETE FROM `agenda` WHERE `id` = :id ");
		$parameters = array(
				':id' => $_GET['id'],
		);
		$result = $sth->execute($parameters);
	}
		
	header('location: /admin/agenda');
	exit;
	
}

if(isset($_POST['save-agenda-point']))
{
	
	$start_time = $_POST['start-time'];
	$end_time = $_POST['end-time'];
	$location = $_POST['location'];
	$description = $_POST['description'];
	$start_date = $_POST['start-date'];
	$explode_start_date = explode('-', $start_date);
	if(count($explode_start_date) != 3)
	{
		$errors[] = 'U moet een geldige start datum opgeven';
	}
	else if(strlen($explode_start_date[0]) == 4)
	{
		$errors[] = 'U moet een geldige start datum opgeven';
	}
	
	
	if($start_date == '')
	{
		$errors[] = 'De datum mag niet leeg zijn';
	}
	if($requested && isset($_POST['reason']) && $_POST['reason'] == '')
	{
		$errors[] = 'U moet een reden omgeven waarom u de afspraak wijzigd';
	}
	
	if($requested && count($errors) == 0)
	{
		$status = $_POST['status'];
		
		$sql = "
			SELECT *
			FROM `dienst_aanvragen`
			WHERE `id` = :id
		";
		$parameters = array(
				':id' => $_GET['id'],
		);
		$sth = $db->prepare($sql);
		$sth->execute($parameters);
		$requested_agenda_point = $sth->fetch(PDO::FETCH_ASSOC);
		
		$sql = "
			UPDATE `dienst_aanvragen`
			SET
				`start_tijd` = :start_tijd,
				`eind_tijd` = :eind_tijd,
				`datum` = STR_TO_DATE(:start_datum, '%d-%m-%Y'),
				`locatie` = :locatie,
				`beschrijving` = :beschrijving,
				`status` = :status
			WHERE `id` = :id
		";
		
		$parameters = array(
			':id' => $_GET['id'],
			':start_tijd' => $start_time,
			':eind_tijd' => $end_time,
			':start_datum' => $start_date,
			':locatie' => $location,
			':beschrijving' => $description,
			':status' => $status,
		);
		
		$sth = $db->prepare($sql);
		
		if($sth->execute($parameters))
		{
			$changes = array();
			
			if($start_time != $user_data['start_tijd'])
			{
				$changes[] = 'Start tijd: ' . $user_data['start_tijd'] . ' is aangepast naar: ' . $start_time;
			}
			if($end_time != $user_data['eind_tijd'])
			{
				$changes[] = 'Eind tijd: ' . $user_data['eind_tijd'] . ' is aangepast naar: ' . $end_time;
			}
			if($start_date != $user_data['datum'])
			{
				$changes[] = 'Datum: ' . $user_data['datum'] . ' is aangepast naar: ' . $start_date;
			}
			if($location != $user_data['locatie'])
			{
				$changes[] = 'Locatie: ' . $user_data['locatie'] . ' is aangepast naar: ' . $location;
			}
			if($description != $user_data['beschrijving'])
			{
				$changes[] = 'Beschrijving: ' . $user_data['beschrijving'] . ' is aangepast naar: ' . $description;
			}
			if($status != $user_data['status'])
			{
				$changes[] = 'Status: ' . ucfirst($user_data['status']) . ' is aangepast naar: ' . ucfirst($status);
			}
			
			// subject
			$subject = 'Wijziging dienstaanvraag - ' . $user_data['servicename'];
			
			if(count($changes) > 0)
			{
				$wijzigingen = 'De volgende wijziging is gemaakt:<br /><br />';
				
				if(count($changes) > 1)
				{
					$wijzigingen = 'De volgende wijzigingen zijn gemaakt:<br /><br />';
				}
				
				$change_data = '';
				foreach($changes as $change)
				{
					$change_data .= $change . '<br />';
				}
					
				// message
				$message = '
					Beste ' . $user_data['naam'] . ', <br /><br />
					De beheerder heeft een wijziging gemaakt in uw aanvraag:<br />
					' . $wijzigingen . '
					' . $change_data . '<br /><br />
					Met de volgende reden: ' . $_POST['reason'] . '<br /><br />
					Met vriendelijke groet,<br />
					' . WEBSITE_NAAM . '
				';
					
				// To send HTML mail, the Content-type header must be set
				$headers  = 'MIME-Version: 1.0' . "\r\n";
				$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
					
				// Additional headers
				$headers .= 'To: ' . $user_data['naam'] . ' <' . $user_data['email'] . '>' . "\r\n";
				$headers .= 'From: ' . WEBSITE_NAAM . ' <' . EMAIL_AFZENDER . '>' . "\r\n";
				
				// Mail it
				mail($user_data['email'], $subject, $message, $headers);
			}
			
			header('location: /admin/agenda');
			exit;
		}
		else {
			$errors[] = 'Er is iets mis gegaan tijdens het updaten';
		}
	}
	else if(!$requested && count($errors) == 0){
		
		$agenda_point_name = $_POST['title'];
		if($agenda_point_name == '')
		{
			$errors[] = 'U moet een geldige titel opgeven';
		}
		$end_date = $_POST['end-date'];
		$explode_end_date = explode('-', $end_date);
		if(count($explode_end_date) != 3)
		{
			$errors[] = 'U moet een geldige start datum opgeven';
		}
		$whole_day = isset($_POST['whole-day']) && $_POST['whole-day'] == 'yes' ? 'true':'false';
		$service = $_POST['service'];
		
		$sql = "
			INSERT INTO `agenda`
				(`id`, `naam`, `start_tijd`, `eind_tijd`, `start_datum`, `eind_datum`, `hele_dag`, `locatie`, `beschrijving`, `dienst`)
			VALUES
				(:id, :naam, :start_tijd, :eind_tijd, STR_TO_DATE(:start_datum, '%d-%m-%Y'), STR_TO_DATE(:eind_datum, '%d-%m-%Y'), :hele_dag, :locatie, :beschrijving, :dienst)
			ON DUPLICATE KEY UPDATE
				`naam` = :naam, 
				`start_tijd` = :start_tijd, 
				`eind_tijd` = :eind_tijd, 
				`start_datum` = STR_TO_DATE(:start_datum, '%d-%m-%Y'), 
				`eind_datum` = STR_TO_DATE(:eind_datum, '%d-%m-%Y'), 
				`hele_dag` = :hele_dag, 
				`locatie` = :locatie, 
				`beschrijving` = :beschrijving,
				`dienst` = :dienst
		";
		
		$id = isset($_GET['id']) ? $_GET['id'] : NULL;
		if($whole_day == 'true')
		{
			$start_time = NULL;
			$end_time = NULL;
		}
		
		$parameters = array(
				':id' => $id,
				':naam' => $agenda_point_name,
				':start_tijd' => $start_time,
				':eind_tijd' => $end_time,
				':start_datum' => $start_date,
				':eind_datum' => $end_date,
				':hele_dag' => $whole_day,
				':locatie' => $location,
				':beschrijving' => $description,
				':dienst' => $service,
		);
		
		$q = $sql;
		foreach($parameters as $key => $value)
		{
			$q = str_replace($key, "'".$value."'", $q);
		}
		//echo $q;exit;
		$sth = $db->prepare($sql);
		
		if(count($errors) == 0)
		{
			if(!$sth->execute($parameters))
			{
				var_dump($sth->errorInfo());exit;
			}
			
			header('location: /admin/agenda');
			exit;
		}
	}
}

if(isset($_POST['edit-agenda-point']))
{
	$agenda_point_name = $_POST['agenda-point-name'];
	$date_explode = explode('-', $_POST['agenda-point-date']);
	$start_date = date('d-m-Y', mktime(0, 0, 0, $date_explode[1], $date_explode[2], $date_explode[0]));
}

if(isset($_GET['id']))
{
	if($requested)
	{
		$date = $user_data['datum'];
		
		$service_name = $user_data['servicename'];
		$status = $user_data['status'];
		
		$agenda_point_name = $user_data['naam'];
		$start_time = $user_data['start_tijd'];
		
		if($start_time != null)
		{
			$start_time = date('H:i',strtotime($start_time));
		}
		
		$end_time = $user_data['eind_tijd'];
		
		if($end_time != null)
		{
			$end_time = date('H:i',strtotime($end_time));
		}
		$location = $user_data['locatie'];
		$description = $user_data['beschrijving'];
	}
	else {
		$sql = "
			SELECT *
			FROM `agenda`
			WHERE `id` = :id
		";
		$parameters = array(
				':id' => $_GET['id'],
		);
		$sth = $db->prepare($sql);
		$sth->execute($parameters);
		$agenda_point = $sth->fetch(PDO::FETCH_ASSOC);
		
		$start_datum_explode = explode('-', $agenda_point['start_datum']);
		$start_datum_dag = $start_datum_explode[2];
		$start_datum_maand = $start_datum_explode[1];
		$start_datum_jaar = $start_datum_explode[0];
		
		$eind_datum_explode = explode('-', $agenda_point['eind_datum']);
		$eind_datum_dag = $eind_datum_explode[2];
		$eind_datum_maand = $eind_datum_explode[1];
		$eind_datum_jaar = $eind_datum_explode[0];
		
		$start_date = $start_datum_dag . '-' . $start_datum_maand . '-' . $start_datum_jaar;
		$end_date = $eind_datum_dag . '-' . $eind_datum_maand . '-' . $eind_datum_jaar;
		
		$whole_day = $agenda_point['hele_dag'] == 'true' ? true : false;
		
		
		$curr_service = $agenda_point['dienst'];
		
		$agenda_point_name = $agenda_point['naam'];
		$start_time = $agenda_point['start_tijd'];
		
		if($start_time != null)
		{
			$start_time = date('H:i',strtotime($start_time));
		}
		
		$end_time = $agenda_point['eind_tijd'];
		
		if($end_time != null)
		{
			$end_time = date('H:i',strtotime($end_time));
		}
		$location = $agenda_point['locatie'];
		$description = $agenda_point['beschrijving'];
	}
}

if(!$requested)
{
	$sth = $db->prepare('SELECT `service_id`, `servicename` FROM `services`');
	$sth->execute();
	$services = $sth->fetchAll(PDO::FETCH_ASSOC);
}
?>
<script type="text/javascript" src="/scripts/agenda.js"></script>
<?php if(isset($errors) && count($errors) > 0):?>
	<div class="message_error">
		<?php foreach($errors as $message):?>
			<p><?php echo $message;?></p>
		<?php endforeach;?>
	</div><br />
<?php endif;?>
<form action="" method="post">
	<table class="simple-table">
		<tr>
			<td colspan="2">
				<input type="button" value="&lt; Vorige" onClick="history.go(-1);" />
				<input type="submit" value="Opslaan" name="save-agenda-point" />
			<?php if(isset($_GET['id'])):?>
				<input type="submit" value="Verwijder afspraak" name="delete-agenda-point" onClick="return confirm('Weet u zeker dat u deze afspraak wilt verwijderen?');"/>
			<?php endif;?>
			</td>
		</tr>
		<tr>
			<?php if(!$requested):?>
				<td colspan="2"><input maxlength="128" class="title" type="text" name="title" value="<?php echo isset($agenda_point_name)?$agenda_point_name:'';?>" /></td>
			<?php endif;?>
		</tr>
		<?php if($requested):?>
			<tr>
				<td>
					<input type="text" id="start-date" name="start-date" value="<?php echo $date;?>" />
				</td>
				<td>
					<input type="text" id="start-time" name="start-time" value="<?php echo isset($start_time)?$start_time:'';?>" />
					tot en met
					<input type="text" id="end-time" name="end-time" value="<?php echo isset($end_time)?$end_time:'';?>" />
				</td>
				</td>
			</tr>
		<?php else:?>
			<tr>
				<td colspan="2">
					<input type="text" id="start-date" name="start-date" value="<?php echo isset($start_date)?$start_date:date('d-m-Y');?>" />
					<input type="text" id="start-time" name="start-time" value="<?php echo isset($start_time)?$start_time:'';?>" />
					tot en met
					<input type="text" id="end-time" name="end-time" value="<?php echo isset($end_time)?$end_time:'';?>" />
					<input type="text" id="end-date" name="end-date" value="<?php echo isset($end_date)?$end_date:date('d-m-Y');?>" />
				</td>
			</tr>
		<?php endif;?>
		<?php if(!$requested):?>
			<tr>
				<td colspan="2">
					<input id="whole_day" type="checkbox" name="whole-day" value="yes" <?php echo isset($whole_day) && $whole_day == true?'checked="checked"':'';?> />
					<label for="whole_day">Hele dag</label>
				</td>
			</tr>
		<?php endif;?>
		<tr><td>&nbsp;</td></tr>
		<tr>
			<td colspan="2"><span><strong>Afspraakgegevens</strong></span></td>
		</tr>
		<tr>
			<td>Waar:</td>
			<td><input maxlength="64" class="location" type="text" name="location" value="<?php echo isset($location)?$location:'';?>" /></td>
		</tr>
		<tr>
			<td colspan="2" valign="top">Beschrijving:</td>
		</tr>
		<tr>
			<td colspan="2"><textarea class="no-editor" name="description"><?php echo isset($description)?$description:'';?></textarea></td>
		</tr>
		<tr>
			<td>Dienst:</td>
			<td>
				<?php if($requested):?>
					<strong><?php echo $service_name;?></strong>
				<?php else:?>
					<select name="service">
						<option value=""></option>
						<?php foreach($services as $service):?>
							<?php $checked = (isset($curr_service) && $curr_service == $service['service_id']) ? 'selected="selected"':''; ?>
							<option <?php echo $checked;?> value="<?php echo $service['service_id'];?>"><?php echo $service['servicename'];?></option>
						<?php endforeach;?>
					</select>
				<?php endif;?>
			</td>
		</tr>
		<?php if($requested):?>
			<tr>
				<td colspan="2">
					<select name="status">
						<option value="aangevraagd" <?php echo $status == 'aangevraagd' ? 'selected="selected"':''?>>Aangevraagd</option>
						<option value="goedgekeurd" <?php echo $status == 'goedgekeurd' ? 'selected="selected"':''?>>Goedgekeurd</option>
						<option value="afgekeurd" <?php echo $status == 'afgekeurd' ? 'selected="selected"':''?>>Afgekeurd</option>
					</select>
				</td>
			</tr>
			<tr><td>&nbsp;</td></tr>
			<tr>
				<td colspan="2"><span><strong>Persoonsgegevens</strong></span></td>
			</tr>
			<tr>
				<td>Naam:</td>
				<td><?php echo $user_data['naam'];?></td>		
			</tr>
			<tr>
				<td>E-mail:</td>
				<td><?php echo $user_data['email'];?></td>		
			</tr>
			<tr>
				<td>Adres:</td>
				<td><?php echo $user_data['adres'];?></td>		
			</tr>
			<tr>
				<td>Postcode:</td>
				<td><?php echo $user_data['postcode'];?></td>		
			</tr>
			<tr>
				<td>Woonplaats:</td>
				<td><?php echo $user_data['woonplaats'];?></td>		
			</tr>
			<tr>
				<td>Telefoon:</td>
				<td><?php echo $user_data['telefoon'] == '' ? '-' : $user_data['telefoon'];?></td>		
			</tr>
			<tr>
				<td>Mobiel:</td>
				<td><?php echo $user_data['mobiel'] == '' ? '-' : $user_data['mobiel'];?></td>		
			</tr>
			<tr><td>&nbsp;</td></tr>
			<tr>
				<td colspan="2"><span><strong>Reden voor wijziging</strong></span></td>
			</tr>
			<tr>
				<td colspan="2"><small>(alleen invoeren indien er wijzigingen zijn)</small></td>
			</tr>
			<tr>
				<td colspan="2"><textarea class="no-editor" name="reason"></textarea></td>
			</tr>
		<?php endif;?>
	</table>
</form>
<div id="start-time-list">
	<?php for($i = 0; $i <= 47; $i += 1):?>
		<?php $half = ($i % 2 != 0)?'30':'00';?>
		<?php $hour = floor($i / 2);?>
		<?php $hour = date('H', mktime($hour,0,0, 1, 1, 1970));?>
		<div><?php echo $hour . ':' . $half; ?></div>
	<?php endfor;?>
</div>
<div id="end-time-list">
	<?php for($i = 0; $i <= 47; $i += 1):?>
		<?php $half = ($i % 2 != 0)?'30':'00';?>
		<?php $hour = floor($i / 2);?>
		<?php $hour = date('H', mktime($hour,0,0, 1, 1, 1970));?>
		<div><?php echo $hour . ':' . $half; ?></div>
	<?php endfor;?>
</div>