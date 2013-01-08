<?php
$dbh=  connectToDatabase();

if(isset($_POST['submitintro'])){
    $homeintrotext=$_POST['homeintrotext'];
    $introtitle=$_POST['introtitle'];
    $date_edited = date ( 'd-m-Y H:i:s' );
    $id=  idarticle($dbh);   
    
    $sql="
	INSERT INTO article (ID, cat_id, date_edited, title, )
	VALUES ()
";
    
}
$requested_appointments = get_requestedAppointments();
?>
<h1>home</h1>
<div class="requested_appointments">
	<?php if(count($requested_appointments) > 0):?>
		<?php foreach($requested_appointments as $appointment):
			$date_explode = explode('-', $appointment['datum']);
			$date = date('d-m-Y', mktime(0, 0, 0, $date_explode[1], $date_explode[2], $date_explode[0]));?>
			<table>
				<tr>
					<td>Naam aanvrager:</td>
					<td><?php echo $appointment['naam'];?></td>
				</tr>
				<tr>
					<td>Datum:</td>
					<td><?php echo $date;?></td>
				</tr>
				<tr>
					<td>Tijd:</td>
					<td>Van:&nbsp;<?php echo date('H:i',strtotime($appointment['start_tijd']));?>&nbsp;Tot:&nbsp;<?php echo date('H:i',strtotime($appointment['eind_tijd']));?></td>
				</tr>
				<tr>
					<td>Waar:</td>
					<td><?php echo $appointment['locatie'] != '' ? $appointment['locatie'] : '-';?></td>
				</tr>
				<tr>
					<td></td><td><a href="/admin/agenda/bewerk/<?php echo $appointment['id'];?>/requested">Bekijk afspraak</a></td>
				</tr>
			</table><br />
		<?php endforeach;?>
	<?php else:?>
		<div>Er zijn momenteel geen afspraken.</div>
	<?php endif;?>
</div>
    <!-- <form action="" method="POST" style="width: 200px;  ">
	<table>
	    <tr>
		<td>
		   <h2>Homepagina tekst introductie aanpassen</h2>
		</td>
	    </tr>
	    <tr>
		<td>
		    <input type="text" name="introtitle" value="Introductie" />
		</td>
	    </tr>
	    <tr>
		<td>
		    <textarea name="homeintrotext" rows="20" cols="70"></textarea>
		</td>
	    </tr>
	    <tr>
		<td><input type="submit" value="submit" name="submitintro" /></td>
	    </tr>
	</table>
    </form> -->