<?php
/*
 * @author Robert-John van Doesburg
 * @klas ICT M1 E1
 * @projectGroup SSJ
 */
session_start();
ob_start();

//Set error rporting
error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', 1);

include DOCROOT . 'inc/functions.inc.php';


if(!isset($_SESSION['admin']) && $_SESSION['admin'] != true)
{
	header('location:/admin/login');
}

$page = isset($_GET['p']) ? str_replace('/', '',$_GET['p']):'home';

if(!file_exists(DOCROOT . 'admin/templates/' . $page . '.php'))
{
	$page = 'home';
}
?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title>Juridische hulp - Admin</title>
		<link rel="stylesheet" type="text/css" href="/styles/admin.css" />
		
		<script type="text/javascript" src="/scripts/jquery.1.8.3.js"></script>
		<script type="text/javascript" src="/scripts/jquery_ui.1.9.2.js"></script>
		<script type="text/javascript" src="/scripts/admin.js"></script>
		<script type="text/javascript" src="/scripts/tiny_mce/tiny_mce.js"></script>
		<script type="text/javascript" src="/scripts/tiny_mce/tiny_mce.init.js"></script>
	</head>
	<body>
		<div id="wrapper">
			<div id="header">
				<div id="menu">
					<ul>
						<li><a href="/admin/home">Home</a></li>
						<li><a href="/admin/agenda">Agenda</a></li>
						<li><a href="/admin/categorie">Categorie</a></li>
						<li><a href="/admin/artikel">Artikelen</a></li>
						<li><a href="/admin/admintarieven">Tarieven</a></li>
						<li><a href="/admin/diensten">Diensten</a></li>
						<li><a href="/admin/downloads">Downloads</a></li>
						<li><a href="/admin/clienten">Cli&euml;nten</a></li>
						<li><a href="/admin/menu">Menu Items</a></li>
						<li class="login_menu_item">
							<a href="/admin/logout">Uitloggen</a>
						</li>
					</ul>
				</div>
			</div>
			<div id="content" class="<?php echo $page;?>">
				<?php include DOCROOT . 'admin/templates/' . $page . '.php';?>
			</div>
			<div id="footer"></div>
		</div>
	</body>
</html>
<?php 
ob_end_flush();
?>