<?php
include(DOCROOT."/inc/mysql.inc.php");

$dbh = connectToDatabase();  // Maak verbinding met de database

if(isset($_GET['option'])) {
    if($_GET['option'] == "delete") {
        $sth = $dbh->prepare("DELETE FROM article WHERE ID=:id");
        $sth->bindParam(":id", $_GET['id']);
        $sth->execute();
    }
}

$sth = $dbh->query("SELECT A.ID,title,C.name AS catname,date_added,date_edited,published FROM article A JOIN category C ON A.cat_id = C.cat_id ORDER BY ID"); // Haal alle artikelen uit de database
$sth->execute();

$res = $sth->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
    <body>
        <table border="1">
            <tr>
                <td>Titel</td> 
                <td>Categorie</td>
                <td>Datum aangemaakt</td>
                <td>Laatst gewijzigd</td> 
                <td>Gepubliceerd</td>
                <td>Bewerk</td>
                <td>Verwijder</td>
            </tr>
            <?php 
                foreach($res as $row) {                                                 // Loop door SQL-resultaten
                    echo("<tr>");
                    echo("<td>".$row['title']."</td>");                                 // Print de titel
                    echo("<td>".$row['catname']."</td>");                               // Print de categorie
                    echo("<td>".$row['date_added']."</td>");                            // Print de datum
                    echo("<td>".$row['date_edited']."</td>");                   
                    echo("<td>".($row['published'] == 1 ? "Ja" : "Nee")."</td>");        // Print de publicatiestatus
                    echo("<td><a href='article.php?option=edit&id=".$row['ID']."'>Bewerk</a></td>");      // Print de bewerk knop
                    echo("<td><a href='".$_SERVER['PHP_SELF']."?option=delete&id=".$row['ID']."'>Verwijder</a></td>");
                    echo("</tr>");
                } 
            ?>
            </tr>
        </table>
    </body>
</html>
