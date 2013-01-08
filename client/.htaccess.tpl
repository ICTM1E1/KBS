php_value auto_prepend_file "C:\wamp\www\KBS\inc\config.inc.php"

Options +FollowSymlinks
RewriteEngine on

RewriteBase /

RewriteCond %{SCRIPT_FILENAME} !-f
RewriteCond %{SCRIPT_FILENAME} !-d

RewriteRule ^login$ /client/login.php [NC]
RewriteRule ^logout$ /client/login.php?action=logout [NC]
RewriteRule ^([a-zA-Z]+)$ /client/index.php?p=$1 [NC]
RewriteRule ^bericht/([0-9]+)$ /client/index.php?p=bericht&id=$1 [NC]
RewriteRule ^berichten/nieuw$ /client/index.php?p=nieuwbericht [NC]
RewriteRule ^berichten/succes$ /client/index.php?p=berichten&status=succes [NC]
RewriteRule ^berichten/faal$ /client/index.php?p=nieuwbericht&status=faal [NC]