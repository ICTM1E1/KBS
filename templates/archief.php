<?php
/*
 * @author Caspar Crop
 * @klas ICT M1 E1
 * @projectGroup SSJ
 */

//connection to database
$dbh = connectToDatabase();
//retreiving current year
$datetoday = date("Y");
?>

<!--Div for the whole page-->
<div id="Archives">

    <!--Div for the title-->
    <div id="Archivetitle">
	<h1>Archief</h1>
    </div>

    <!--Div containing the actual archive-->
    <div id="Archive">
	<ul>
	    <?php
	    //go through and display every year
	    for ($iy = $datetoday; $iy >= 2013; $iy = $iy - 1) {
		$dyear = $iy;
		echo ("<div id='archiveyear'><h2>" . $dyear . "</h2>");
		//go through every month
		for ($im = 12; $im >= 1; $im = $im - 1) {
		    $dmonth = $im;
		    //retreive articles from selected month
		    $result = retreivearchive($dyear, $dmonth, $dbh);
		    //don't show month if there are no articles published in that month
		    if (count($result) != 0) {
			echo("<div id='archivemonth'><h3>" . archivemonths($dmonth) . "</h3>");
			//display every row of articles for this month
			foreach ($result as $row) {
			    ?>
			    <!--Show link linking to the article clicked on-->
			    <li> <a href="<?php echo '/actualiteit/'.$row['ID']?>"> <?php echo $row['title']; ?></a> </li>
			    <?php
			}
			echo '</div>';
		    }
		}
		echo '</div>';
	    }
	    ?>
	</ul>
    </div>

</div>
