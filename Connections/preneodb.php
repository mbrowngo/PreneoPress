<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_preneodb = "kent.preneo.org";
$database_preneodb = "preneodb";
$username_preneodb = "dataconnect";
$password_preneodb = "zaq!xsw2";
$preneodb = mysql_pconnect($hostname_preneodb, $username_preneodb, $password_preneodb) or trigger_error(mysql_error(),E_USER_ERROR); 
?>

