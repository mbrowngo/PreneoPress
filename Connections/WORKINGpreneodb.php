<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_preneodb = "MySQLB12.webcontrolcenter.com";
$database_preneodb = "preneodb";
$username_preneodb = "mbrown";
$password_preneodb = "a1q1a1q1";
$preneodb = mysql_pconnect($hostname_preneo, $username_preneo, $password_preneo) or trigger_error(mysql_error(),E_USER_ERROR); 
?>

