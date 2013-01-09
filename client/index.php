<?php 
session_start();
ob_start();

//Set error rporting
error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', 1);

include DOCROOT . 'inc/functions.inc.php'; // bevat functie om verbinding te maken met een database

if(!isset($_SESSION['client']) && $_SESSION['client'] != true)
{
	header('location:/client/login');
}

$page = isset($_GET['p']) ? $_GET['p']:'home';

if(!file_exists(DOCROOT . 'client/templates/' . $page . '.php'))
{
	$page = 'home';
}

$unread = "";

$dbh = connectToDatabase();

$sth = $dbh->prepare("SELECT id FROM berichten WHERE ontvanger=:cid AND gelezen='0'");
$sth->bindParam(":cid", $_SESSION['clientid']);
$sth->execute();
$count = $sth->rowCount();

if($count > 0) {
	$unread = " (".$count.")";
}
?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title>Juridische hulp - Cli&euml;nt gedeelte</title>
		<link rel="stylesheet" type="text/css" href="/styles/client.css" />
		<link rel="stylesheet" type="text/css" href="/styles/jqueryui/jquery-ui-1.9.2.custom.min.css" />
		
		<script type="text/javascript" src="/scripts/jquery.1.8.3.js"></script>
		<script type="text/javascript" src="/scripts/jquery_ui.1.9.2.js"></script>
		<script type="text/javascript" src="/scripts/tiny_mce/tiny_mce.js"></script>
		<script type="text/javascript" src="/scripts/tiny_mce/tiny_mce.init.js"></script>
	</head>
	<body>
		<div id="wrapper">
			<div id="header">
				<div id="menu">
					<ul>
						<li><a href="/client/home">Home</a></li>
						<li><a href="/client/berichten">Berichten<?php echo($unread);?></a></li>
						<li class="login_menu_item">
						    <a href="/">Homepagina</a>
						    <a href="/client/logout">Uitloggen</a>
						</li>
					</ul>
				</div>
			</div>
			<div id="content">
				<?php include DOCROOT . 'client/templates/' . $page . '.php';?>
			</div>
			<div id="footer"></div>
		</div>
	</body>
</html>
<?php 
ob_end_flush();
?>