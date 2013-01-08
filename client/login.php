<?php
session_start();

include_once(DOCROOT . 'inc/functions.inc.php'); // bevat functie om verbinding te maken met een database

if(isset($_GET['action']) && $_GET['action'] == 'logout')
{
	unset($_SESSION['client']);
	unset($_SESSION['clientid']);
	unset($_SESSION['clientname']);
	session_destroy();
	header('location:/client/login');
	exit;
}


if(isset($_SESSION['client']) && $_SESSION['client'] == true)
{
	header('location:/client/home');
	exit;
}

if(isset($_POST['submit']))
{
	$errors = array();
	if(!isset($_POST['login'])) {               // Controleren of beide velden (naam + wachtwoord zijn ingevuld)
	    $errors[] = 'Geen gebruikersnaam ingevuld!';
	} else if(!isset($_POST['password'])) {
		$errors[] = 'Geen wachtwoord ingevuld!';
	}
        else {
            $dbh = connectToDatabase(); // Database verbinding wordt aangemaakt en vastgelegd in $dbh

            $user = $_POST['login'];   
            $pass = hash('sha256', $_POST['password']);
	    
            $sth = $dbh->prepare("SELECT id FROM users WHERE username=:user AND password=:pass");
            $sth->bindParam(":user", $user);
            $sth->bindParam(":pass", $pass);
            $sth->execute();
	    $res = $sth->fetchAll(PDO::FETCH_ASSOC);
	    
            if($sth->rowCount())
            {
            	$_SESSION['client'] = true;
		$_SESSION['clientid'] = $res[0]['id'];
		
            	header('location:/client/home');
            }
            else {
            	$errors[] = 'Combinatie gebruikersnaam/wachtwoord onjuist.';
            }
            //print_r($sth->fetchAll(PDO::FETCH_ASSOC));
	}
}
?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title>Juridische hulp</title>
		<link rel="stylesheet" type="text/css" href="/styles/client.css" />
	</head>
	<body>
		<?php if(isset($errors) && count($errors) > 0):?>
			<div class="message_error" style="text-align:center;">
				<?php foreach($errors as $message):?>
					<p><?php echo $message;?></p>
				<?php endforeach;?>
			</div><br />
		<?php endif;?>
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