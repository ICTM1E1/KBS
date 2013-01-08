<?php
$dbh=  connectToDatabase();

if(isset($_POST['submitintro'])){
    $homeintrotext=$_POST['homeintrotext'];
    $introtitle=$_POST['introtitle'];
    $date_edited = date ( 'd-m-Y H:i:s' );
    $id=  idarticle($dbh);
//+category= Homepagina artikelen
//published=1    
    
    $sql="INSERT INTO article";
    
    
    
}

?>
<h1>home</h1>


    <form action="" method="POST" style="width: 200px;  ">
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
    </form>