<?php 
/*
 * @author Robert-John van Doesburg
 * @klas ICT M1 E1
 * @projectGroup SSJ
 */
?>
<script type="text/javascript" src="/scripts/agenda.js"></script>
<?php 
$result = getAgendaMonth();
?>


<a href="#" id="previous_month">&lt; vorige maand</a><br />
<a href="#" id="next_month">Volgende maand &gt;</a>
<h3 id="current_date"><?php echo $result['month_name'];?> <?php echo $result['year'];?></h3>
	<div id="ag-page">
		<table id="ag-days">
				<tr>
					<th class="ag-day">Maandag</th>
					<th class="ag-day">Dinsdag</th>
					<th class="ag-day">Woensdag</th>
					<th class="ag-day">Donderdag</th>
					<th class="ag-day">Vrijdag</th>
					<th class="ag-day">Zaterdag</th>
					<th class="ag-day">Zondag</th>
				</tr>
		</table>
		<div id="ag-container">
			<?php echo $result['data'];?>
		</div>
	</div>
<input type="hidden" value="<?php echo $result['month']?>" id="current_month" />
<input type="hidden" value="<?php echo $result['year']?>" id="current_year" />
<div id="bubble-main">
	<div class="bubble-content">
		<form action="/admin/agenda/bewerk" method="post">
			<h3>Afspraak</h3>
			<div class="bubble-close" style="float:right;">X</div>
			<table>
				<tr>
					<td>Wanneer:</td>
					<td id="selected-date"></td>
				</tr>
				<tr>
					<td>Naam:</td>
					<td><input type="text" name="agenda-point-name" id="bubble-what" /> </td>
				</tr>
			</table>
			<div class="appointment-options">
				<input class="left" id="make-appointment" type="button" value="Afspraak maken" />
				<input class="right edit-appointment" type="submit" value="Afspraak Bewerken" name="edit-agenda-point" />
			</div>
			
			<input type="hidden" name="agenda-point-date" id="selected-date-value" />
		</form>
	</div>
	<div class="bottom-prong">
		<div class="prong-dk"></div>
		<div class="prong-lt"></div>
	</div>
</div>

<div id="bubble-edit-appointment">
	<div class="bubble-content">
		<h3 id="appointment-name"></h3>
		<div class="bubble-close" style="float:right;">X</div>
		<div id="appointment-date"></div>
		<div class="appointment-options">
			<input class="left" type="button" id="delete-appointment" value="Verwijder definitief" />
			<input class="right edit-appointment" type="button" value="Afspraak bewerken" id="edit-existing-appointment" />
		</div>
		<input type="hidden" id="appointment-id" />
	</div>
	<div class="bottom-prong">
		<div class="prong-dk"></div>
		<div class="prong-lt"></div>
	</div>
</div>
<div id="bubble-all-appointments">
		<div class="bubble-close" style="float:right;">X</div>
	<div class="bubble-content">
		
	</div>
</div>
<script>
$(document).ready(function(){
	$('#start-date').datepicker();	
});

//$('#start-date').datepicker({dateFormat: 'dd-mm-yy'});
</script>