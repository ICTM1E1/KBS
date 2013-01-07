<?php
/*
 * @author Robert-John van Doesburg
 * @klas ICT M1 E1
 * @projectGroup SSJ
 */
session_start ();
ob_start ();

// Set error rporting
error_reporting ( E_ALL | E_STRICT );
ini_set ( 'display_errors', 1 );

include DOCROOT . 'inc/functions.inc.php';

$page = isset ( $_GET ['p'] ) ? str_replace ( '/', '', $_GET ['p'] ) : 'home';

if (! file_exists ( DOCROOT . 'templates/' . $page . '.php' )) {
	header ( 'HTTP/1.0 404 Not Found' );
	$page = '404';
}

?>
<!--[if IE 8]><html class="ie8"><![endif]-->
<!--[if IE 9]><html class="ie9"><![endif]-->
<!--[if !IE]><html><![endif]-->
<head>
<meta http-equiv="X-UA-Compatible" content="IE=9" >
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Juridische hulp</title>
<link rel="stylesheet" type="text/css" href="/styles/default.css" />

<script type="text/javascript" src="/scripts/jquery.1.8.3.js"></script>
<script type="text/javascript" src="/scripts/jquery_ui.1.9.2.js"></script>
<script type="text/javascript" src="/scripts/default.js"></script>
</head>
<body>
	<div id="wrapper">
		<div id="header">
			<div id="header-image">
				<a href="/"> <img src="/images/header.png" alt="header" />
				</a>
			</div>
			<div id="menu">
				<ul>
					<li><a href="/home">Home</a></li>
					<li><span>Bedrijven</span>
						<ul class="sub-menu">
							<li><span>Arbeidsrecht</span>
								<ul>
									<li><a href="/bedrijven/arbeidsrecht/werk-en-ontslag">Werk en
											ontslag</a></li>
									<li><a href="/bedrijven/arbeidsrecht/uitkering">Uitkering</a></li>
								</ul></li>
							<li><span>Contractenrecht</span>
								<ul>
									<li><a href="/bedrijven/contractrechten/wonen">Wonen</a></li>
									<li><a href="/bedrijven/contractrechten/reizen">Reizen</a></li>
									<li><a href="/bedrijven/contractrechten/verkeer">Verkeer</a></li>
									<li><a href="/bedrijven/contractrechten/internet-energie">Internet/energie</a></li>
								</ul></li>
						</ul></li>
					<li><span>Particulier</span>
						<ul class="sub-menu">
							<li><span>Arbeidsrecht</span>
								<ul>
									<li><a href="/particulier/arbeidsrecht/werk-en-ontslag">Werk en
											ontslag</a></li>
									<li><a href="/particulier/arbeidsrecht/uitkering">Uitkering</a></li>
								</ul></li>
							<li><span>Contractenrecht</span>
								<ul>
									<li><a href="/particulier/contractrechten/wonen">Wonen</a></li>
									<li><a href="/particulier/contractrechten/reizen">Reizen</a></li>
									<li><a href="/particulier/contractrechten/verkeer">Verkeer</a></li>
									<li><a href="/particulier/contractrechten/internet-energie">Internet/energie</a></li>
								</ul></li>
						</ul></li>
					<li><a href="/diensten">Diensten</a></li>
					<li><a href="/downloads">Downloads</a></li>
					<li><a href="/contact">Contact</a></li>
					<li><a href="/tarieven">Tarieven</a></li>
					<li><a href="/archief">Archief</a></li>
					<li class="login_menu_item"><a href="/client/login">Inloggen</a></li>
				</ul>
			</div>
		</div>
		<div id="content">
			<div id="left-content" class="<?php echo $page; ?>">
		    <?php include '/templates/' . $page . '.php'; ?>
		</div>
			<div id="right-content">
				<form id="search-form" action="/zoekresultaten" method="post">
					<input type="text" name="zoekwoord123" placeholder="Zoeken" /> <input
						type="image" src="/images/searcher.png" value="" />
				</form>
			<div class="right-content-archive">
		    	<?php echo sortArticles(connectToDatabase());?>
		    </div>
		</div>
		</div>
		<div id="footer">
			<div class="copyright">Copyright &copy; 2013</div>
			<div class="social-media">
				<a target="_blank" href="http://facebook.com"><img src="/images/facebook.png" /></a>
				<a target="_blank" href="http://twitter.com"><img src="/images/twitter.png" /></a>
				<a target="_blank" href="http://linkedin.com"><img src="/images/linkedin.png" /></a>
			</div>
		</div>
	</div>
</body>
</html>
<?php
ob_end_flush ();