<?php
/*
 * @author Caspar crop && Robert-John van  Doesburg
 * @klas ICT M1 E1
 * @projectGroup SSJ
 */
//database connection
$dbh = connectToDatabase ();

//sql query for retreiving the home introduction
$sth = $dbh->prepare("SELECT article_id FROM menu_item WHERE parent_item='Home' and child_item='Home'");
//execute the sql statement
$sth->execute();
//retreiving the data from the query
$res = $sth->fetch(PDO::FETCH_ASSOC);

//set the variable id
$id = $res['article_id'];

//retreive article for homepage introduction
$sth1 = $dbh->prepare ( "SELECT * FROM article WHERE ID=:id" );
//bind variable id
$sth1->bindParam(":id", $id);
//execute the query
$sth1->execute();
//retreive data from query
$res1 = $sth1->fetch(PDO::FETCH_ASSOC);

?>

<div>
    <div id="home-upper">
    	<?php 
	//shows the intoduction text
	echo $res1['text'];
	?>
    </div>
    
    <div id="home-news">
	<?php 
	//retreive the 3 latest news articles
	$result=  retreivenewsarticle($dbh);
	//for each of the 3 articles
	foreach($result as $row){
	    //show the news article
	    echo'<div id="home-article">';
	    echo('<h3>'.$row["title"].'</h3>');
	    echo'<br/>';
	    echo($row["TEXT"]);
	    echo'</div>';
	}
	?>
    </div>
    
    
    
</div>