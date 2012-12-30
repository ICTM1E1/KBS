<?php
/*
 * @author Jelle Kapitein & Richard van den Hoorn
 * @klas ICT M1 E1
 * @projectGroup SSJ
 */
if (isset ( $_GET ['parent_item'] )) {
	$dbh = connectToDatabase ();
	$parent=$_GET ["parent_item"];
	$child=$_GET ["child_item"];
	$sth = $dbh->prepare ( "SELECT article_id FROM menu_item WHERE parent_item='$parent' and child_item='$child'" );
	//$sth->bindParam ( ":parent", $parent );
	//$sth->bindParam ( ":child", $child );
	$sth->execute ();
	
	$res = $sth->fetchAll ( PDO::FETCH_ASSOC );
	foreach ( $res as $row ) {
		$id = $row ['article_id'];
	}
}
if (isset ( $_GET ['id'] )) {
	$id = $_GET ['id'];
}

if (isset ( $id )) {
	$dbh = connectToDatabase ();
	$sth = $dbh->prepare ( "SELECT * FROM article WHERE ID=:id" );
	$sth->bindParam ( ":id", $id);
	$sth->execute ();
	
	$res = $sth->fetchAll ( PDO::FETCH_ASSOC );
}

if (isset ( $res )) {
	foreach ( $res as $row ) {
		$date = date ( "d-m-Y H:i:s", strtotime ( $row ['date_added'] ) );
		$datee = date ( "d-m-Y H:i:s", strtotime ( $row ['date_edited'] ) );
		
		?>

<div class="artikel" id="title">
	<h2><?php echo $row['title'] ?></h2>
</div>
<div class="artikel" id="dates">
	<i>Aangemaakt: <?php echo $date ?></i>
    <?php if($date != $datee) {?>
    <i><br />Laatst gewijzigd: <?php echo $datee ?></i>
    <?php } ?>
</div>
<br><hr><br>
<div class="artikel" id="text">
    <?php echo $row['text']?>
</div>

<?php
	}
}
?>