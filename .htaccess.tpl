php_value auto_prepend_file "inc\config.inc.php"
Options +FollowSymlinks
RewriteEngine on

RewriteBase /

RewriteCond %{SCRIPT_FILENAME} !-f
RewriteCond %{SCRIPT_FILENAME} !-d

RewriteRule ^([a-zA-Z]+)$ index.php?p=$1 [NC]

RewriteRule ^zoekresultaten/([0-9]+)$ index.php?p=zoekresultaten&page=$1 [NC]
RewriteRule ^artikel/([0-9]+)$ index.php?p=artikel&id=$1 [NC]
RewriteRule ^downloads/([0-9]+)$ index.php?p=downloads&page=$1 [NC]
RewriteRule ^tarieven/([0-9]+)$ index.php?p=tarieven&page=$1 [NC]
RewriteRule ^actualiteit/([0-9]+)$ index.php?p=artikel&id=$1 [NC]
RewriteRule ^diensten/([0-9]+)$ index.php?p=diensten&id=$1 [NC]
RewriteRule ^dienstaanvraag/([0-9]+)$ index.php?p=dienstaanvraag&id=$1
RewriteRule ^dienstaanvraag/([0-9]+)/(gelukt|mislukt)$ index.php?p=dienstaanvraag&id=$1&status=$2

RewriteCond %{REQUEST_FILENAME} !\.(gif|png|jpg|jpeg|jfif|bmp|css|js)$ [NC]
RewriteRule ^([a-zA-Z]+)/([a-zA-Z]+)/(.*)$ index.php?p=artikel&parent_item=$1&child_item=$3 [NC]