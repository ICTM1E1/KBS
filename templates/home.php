<?php
/*
 * @author Caspar crop
 * @klas ICT M1 E1
 * @projectGroup SSJ
 */
$dbh=  connectToDatabase();
?>
<div>
    <div id="home-upper">
	    Lage soms deel stad ad vast nu erin. Zij wie met vermijden nutteloos tinmijnen.
	    Kan wegen wilde drong reden dal naast tin van. Stampers roestige pyrieten ad te. 
	    Beroemde eveneens te laatsten contract te. Ten gronds weldra gevolg die passen steeds zonder. 
	    Singapore inderdaad zee elk gedeelten ons tin afscheidt plaatsing afstanden. 
	    En deze in ze dure mier liet. Al anson af noemt op kreeg omdat china.
	    Wordt of ad begin varen en. Deel is ik alle te geen. Nu wijk zout te ze is acre.
	    Andere ceylon om te kriang lieden. Monopolie bezwarend stroomend gesteente na of de afstanden overwaard nu.
	    Ongunstige schoonheid karrijders af nu europeanen geruineerd weelderige. Is heuvel ruimer slotte er om.

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