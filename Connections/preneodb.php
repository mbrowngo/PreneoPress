<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_preneodb = "xxx";
$database_preneodb = "xxx";
$username_preneodb = "xxx";
$password_preneodb = "xxx";
$preneodb = mysql_pconnect($hostname_preneodb, $username_preneodb, $password_preneodb) or trigger_error(mysql_error(),E_USER_ERROR); 
?>

