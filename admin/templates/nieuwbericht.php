<?php

if(isset($_POST['submit'])) {
    
}

if(isset($_GET['naam'])) {
    $naam = $_GET['naam'];
} else {
    $naam = "";
}

?>

<table class="simple-table">
    <tr>
	<td>Titel:</td>
	<td><input type="text" name="titel" size="40" value=""/></td>
    </tr>
    <tr>
	<td>Ontvanger:</td>
	<td><input type="text" name="ontvanger"size="40" value="<?php echo($naam); ?>"/></td>
    </tr>
    <tr>
	<td colspan="2">Bericht:</td>
    </tr>
    <tr>
	<td colspan="2"><textarea name="text" rows="20" cols="70"></textarea></td>
    </tr>
    <tr>
	<td><input type="submit" value="Versturen" name="submit"></td>
	<td><input type="button" value="Annuleren" onclick="window.location = '/admin/berichten'"/></td>
    </tr>
	
</table>