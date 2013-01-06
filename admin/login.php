<?php
session_start();
include_once(DOCROOT . 'inc/functions.inc.php'); // bevat functie om verbinding te maken met een database

if(isset($_GET['action']) && $_GET['action'] == 'logout')
{
	unset($_SESSION['admin']);
	session_destroy();
	header('location:/admin/login');
	exit;
}

if(isset($_SESSION['admin']) && $_SESSION['admin'] == true)
{
	header('location:/admin/home');
	exit;
}

if(isset($_POST['submit']))
{
	if(!isset($_POST['login'])) {               // Controleren of beide velden (naam + wachtwoord zijn ingevuld)
	    print("Geen gebruikersnaam ingevuld!");
	} else if(!isset($_POST['password'])) {
	    print("Geen wachtwoord ingevuld!");
	}
        else {
            $dbh = connectToDatabase(); // Database verbinding wordt aangemaakt en vastgelegd in $dbh

            $user = $_POST['login'];   
            $pass = hash('sha256', $_POST['password']);

            $sth = $dbh->prepare("SELECT * FROM users WHERE username=:user AND password=:pass AND `admin` = 1");
            $sth->bindParam(":user", $user);
            $sth->bindParam(":pass", $pass);
            $sth->execute();
            if($sth->rowCount())
            {
            	$_SESSION['admin'] = true;
            	
            	header('location:/admin/home');
            	exit;
            }
            else {
            	echo 'combinatie gebruikersnaam/wachtwoord onjuist.';
            }
            //print_r($sth->fetchAll(PDO::FETCH_ASSOC));
	}
}
?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title>Juridische hulp</title>
		<link rel="stylesheet" type="text/css" href="/styles/admin.css" />
	</head>
	<body>
		<div id="login-form">
			<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
				<table>
					<tr>
						<td>Gebruikersnaam:</td>
					</tr>
					<tr>
						<td><input type="text" name="login" value="test"/></td>
					</tr>
					<tr>
						<td>Wachtwoord:</td>
					</tr>
					<tr>
						<td><input type="password" name="password" value="test"/></td>
					</tr>
					<tr>
						<td><input type="submit" name="submit" value="Inloggen"/></td>
					</tr>
				</table>
			</form>
		</div>
	</body>
</html>