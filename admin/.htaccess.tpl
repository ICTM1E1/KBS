php_value auto_prepend_file "inc\config.inc.php"

Options +FollowSymlinks
RewriteEngine on

RewriteBase /

RewriteCond %{SCRIPT_FILENAME} !-f
RewriteCond %{SCRIPT_FILENAME} !-d

RewriteRule ^login$ /admin/login.php [NC]
RewriteRule ^wijzigtarief/([0-9]+)$ /admin/index.php?p=wijzigtarief&id=$1 [NC]
RewriteRule ^logout$ /admin/login.php?action=logout [NC]
RewriteRule ^artikel/nieuw$ /admin/index.php?p=bewerkartikel&option=new [NC]
RewriteRule ^artikel/bewerk/([0-9]+)$ /admin/index.php?p=bewerkartikel&option=edit&id=$1 [NC]
RewriteRule ^artikel/verwijder/([0-9]+)$ /admin/index.php?p=artikel&option=delete&id=$1 [NC]
RewriteRule ^artikel/([a-zA-Z]+)$ /admin/index.php?p=artikel&case=$1 [NC]
RewriteRule ^categorie/nieuw$ /admin/index.php?p=bewerkcategorie&option=new [NC]
RewriteRule ^categorie/bewerk/([0-9]+)$ /admin/index.php?p=bewerkcategorie&option=edit&id=$1 [NC]
RewriteRule ^categorie/verwijder/([0-9]+)$ /admin/index.php?p=categorie&option=delete&id=$1 [NC]
RewriteRule ^categorie/([a-zA-Z]+)$ /admin/index.php?p=categorie&case=$1 [NC]
RewriteRule ^diensten/bewerk/([0-9]+)$ /admin/index.php?p=bewerkdiensten&option=edit&id=$1 [NC]
RewriteRule ^([a-zA-Z]+)$ /admin/index.php?p=$1 [NC]
RewriteRule ^downloads/delete/([0-9]+)$ /admin/index.php?p=downloads&action=delete&ID=$1 [NC]
RewriteRule ^admintarieven/([0-9]+)$ /admin/index.php?p=admintarieven&page=$1 [NC]
RewriteRule ^diensten/nieuw$ /admin/index.php?p=bewerkdiensten&option=new [NC]
RewriteRule ^diensten/([a-zA-Z]+)$ /admin/index.php?p=diensten&case=$1 [NC]