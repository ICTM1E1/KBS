<?php
/*
 * @author Caspar crop && Robert-John van  Doesburg
 * @klas ICT M1 E1
 * @projectGroup SSJ
 */

$dbh = connectToDatabase ();

$sth = $dbh->prepare("SELECT article_id FROM menu_item WHERE parent_item='Home' and child_item='Home'");
$sth->execute();
$res = $sth->fetch(PDO::FETCH_ASSOC);
$id = $res['article_id'];

$sth = $dbh->prepare ( "SELECT * FROM article WHERE ID=:id" );
$sth->bindParam(":id", $id);
$sth->execute();

$res = $sth->fetch(PDO::FETCH_ASSOC);

?>

<div>
    <div id="home-upper">
    	<?php echo $res['text'];?>
    </div>
    
    <div id="home-news">
	<?php 
	$result=  retreivenewsarticle($dbh);
	foreach($result as $row){
	    echo'<div id="home-article">';
	    echo('<h3>'.$row["title"].'</h3>');
	    echo'<br/>';
	    echo($row["TEXT"]);
	    echo'</div>';
	}
	?>
    </div>
    
    
    
</div>