<?php
define('DOCROOT', dirname(__FILE__) . '/../');
define('SERVERPATH', 'http://kbs.nl');

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_PORT', '');
define('DB_DATABASE', 'kbs');

define('EMAIL_KLANT', 'maartendeboy@hotmail.com');
define('EMAIL_AFZENDER', 'geen-antwoord@juridische-hulp.nl');
define('WEBSITE_NAAM', 'Juridische Hulp');
date_default_timezone_set('Europe/Amsterdam');
setlocale(LC_TIME, array('Dutch_Netherlands', 'Dutch', 'nl_NL', 'nl', 'nl_NL.UTF-8'));