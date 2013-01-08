<?php
/*
 * @author Richard van den Hoorn
 * @klas ICT M1 E1
 * @projectGroup SSJ
 */

$dbh = connectToDatabase (); // Maak verbinding met de database

$sql1="SELECT * FROM menu_item";
$res = selectquery($sql1, $dbh);

$sql2="SELECT * FROM article WHERE cat_id=(SELECT cat_id FROM category WHERE name='Menu-items')";
$articles = selectquery($sql2, $dbh);

function haalartikeltitelop($id) {
	$dbh = connectToDatabase ();
	$sql="SELECT title FROM article WHERE id=$id";
	// $sth->bindParam ( ":id", $id );
	$articles = selectquery($sql, $dbh);
	
	foreach ( $articles as $row ) {
		$article = $row ['title'];
	}
	if (isset ( $article )) {
		return $article;
	}
	else{
		return 'Geen artikel geselecteerd';
	}

}
$style = '';
$statusText = "";

if (isset ( $_POST ['submit'] )) {
	$article_id = $_POST ['article_id'];
	$id = $_POST ['id'];
	
	$sth = $dbh->query ( "UPDATE menu_item SET article_id=$article_id WHERE id=$id" );
	// $sth->bindParam(":article_id", $article_id);
	// $sth->bindParam(":parent_item", $parent_item);
	// $sth->bindParam(":child_item", $child_item);
	$result = $sth->execute ();
	if($result == true){
		$style = 'message_success';
		$statusText = "Wijziging succesvol opgeslagen.";
	}
	else{
		$style = 'message_error';
		$statusText = "Er is een fout opgetreden tijdens het opslaan van de wijziging, de wijziging is niet doorgevoerd!";
	}
	
	header ( "Location: " );
	exit ();
}

?>
<div class="<?php echo $style; ?>">
	<p><?php echo $statusText; ?></p>
</div>

<table class="hover">
	<tr>
		<th>Hoofdmenu item</th>
		<th>Submenu item</th>
		<th>Gelinkt artikel</th>
		<th>Wijzig</th>
	</tr>
<?php
foreach ( $res as $row ) { // Loop door SQL-resultaten
	?>
    	<tr>
    		<form action="" method="post">
			<td><input type="hidden" name="id" value="<?php echo $row['id']; ?>"><?php echo $row['parent_item']; ?></td>
			<td><?php echo $row['child_item']; ?></td>
			<td><?php echo haalartikeltitelop($row['article_id']); ?></td>
			<td width="200" ><input type="button" href="javascript:;" onClick="document.getElementById('<?php echo $row['id'];?>').style.display='block'" style='cursor: pointer' value="Wijzig">
				<div id="<?php echo $row["id"];?>" style="display: none;">
					
					<select name="article_id" onChange="this.form.submit();">
    					<?php
						$article = $row ['article_id'];
						foreach ( $articles as $row ) {
							$id = $row ["ID"];
								$title = $row ["title"];
								// Check which article is to be selected
								if ($id == $article) {
									$selected = " selected";
								} else {
									$selected = '';
								}
								print ("<option value=\"$id\"" . $selected . ">") ; // print articles
								print ($title . "</option>") ;
							}
							?>
					</select> 
					<input type="submit" name="submit" value="Opslaan">
					
				</div>
			</td>
			</form>
		</tr>
	
 	<?php
}
?>
	
    </table>

