<?php 
/*
 * @author Robert-John van Doesburg
 * @klas ICT M1 E1
 * @projectGroup SSJ
 */

$db = connectToDatabase();

if(isset($_POST['delete-agenda-point']))
{
	$sth = $db->prepare("DELETE FROM `agenda` WHERE `id` = :id ");
	$parameters = array(
			':id' => $_GET['id'],
	);
	$result = $sth->execute($parameters);
	
	
	header('location: /admin/agenda');
	exit;
}

if(isset($_POST['save-agenda-point']))
{
	$agenda_point_name = $_POST['title'];
	$start_date = $_POST['start-date'];
	$start_time = $_POST['start-time'];
	$end_time = $_POST['end-time'];
	$end_date = $_POST['end-date'];
	$whole_day = isset($_POST['whole-day']) && $_POST['whole-day'] == 'yes' ? 'true':'false';
	$location = $_POST['location'];
	$description = $_POST['description'];
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
	if($whole_day)
	{
		$start_time = 'NULL';
		$end_time = 'NULL';
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
	
	if(!$sth->execute($parameters))
	{
		var_dump($sth->errorInfo());exit;
	}
	
	header('location: /admin/agenda');
	exit;
}

if(isset($_POST['edit-agenda-point']))
{
	$agenda_point_name = $_POST['agenda-point-name'];
	$date_explode = explode('-', $_POST['agenda-point-date']);
	$start_date = date('d-m-Y', mktime(0, 0, 0, $date_explode[1], $date_explode[2], $date_explode[0]));
}

if(isset($_GET['id']))
{
	
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
	
	$agenda_point_name = $agenda_point['naam'];
	$start_date = $start_datum_dag . '-' . $start_datum_maand . '-' . $start_datum_jaar;
	$start_time = $agenda_point['start_tijd'];
	if($start_time != null)
	{
		$start_time_explode = explode(':', $start_time);
		$start_time = $start_time_explode[0] . ':' . $start_time_explode[1];
	}
	
	$end_time = $agenda_point['eind_tijd'];
	
	if($end_time != null)
	{
		$end_time_explode = explode(':', $end_time);
		$end_time = $end_time_explode[0] . ':' . $end_time_explode[1];
	}
	$end_date = $eind_datum_dag . '-' . $eind_datum_maand . '-' . $eind_datum_jaar;
	
	$whole_day = $agenda_point['hele_dag'] == 'true' ? true : false;
	
	$location = $agenda_point['locatie'];
	$description = $agenda_point['beschrijving'];
	
	$curr_service = $agenda_point['dienst'];
	
}

$sth = $db->prepare('SELECT `service_id`, `servicename` FROM `services`');
$sth->execute($parameters);
$services = $sth->fetchAll(PDO::FETCH_ASSOC);
?>
<script type="text/javascript" src="/scripts/agenda.js"></script>
<form action="" method="post">
	<table class="simple-table">
		<tr>
			<td><input type="submit" value="Opslaan" name="save-agenda-point" /></td>
			<td><input type="submit" value="Verwijder afspraak" name="delete-agenda-point" /></td>
		</tr>
		<tr>
			<td colspan="2"><input class="title" type="text" name="title" value="<?php echo isset($agenda_point_name)?$agenda_point_name:'';?>" /></td>
		</tr>
		<tr>
			<td colspan="2">
				<input type="text" id="start-date" name="start-date" value="<?php echo isset($start_date)?$start_date:date('d-m-Y');?>" />
				<input type="text" id="start-time" name="start-time" value="<?php echo isset($start_time)?$start_time:'';?>" />
				tot en met
				<input type="text" id="end-time" name="end-time" value="<?php echo isset($end_time)?$end_time:'';?>" />
				<input type="text" id="end-date" name="end-date" value="<?php echo isset($end_date)?$end_date:date('d-m-Y');?>" />
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<input id="whole_day" type="checkbox" name="whole-day" value="yes" <?php echo isset($whole_day) && $whole_day == true?'checked="checked"':'';?> />
				<label for="whole_day">Hele dag</label>
			</td>
		</tr>
		<tr><td>&nbsp;</td></tr>
		<tr>
			<td colspan="2"><span><strong>Afspraakgegevens</strong></span></td>
		</tr>
		<tr>
			<td>Waar:</td>
			<td><input class="location" type="text" name="location" value="<?php echo isset($location)?$location:'';?>" /></td>
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
				<select name="service">
					<option value=""></option>
					<?php foreach($services as $service):?>
						<?php $checked = (isset($curr_service) && $curr_service == $service['service_id']) ? 'selected="selected"':''; ?>
						<option <?php echo $checked;?> value="<?php echo $service['service_id'];?>"><?php echo $service['servicename'];?></option>
					<?php endforeach;?>
				</select>
			</td>
		</tr>
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