<?php
$cfg['sql']['host'] = "localhost";
$cfg['sql']['user'] = "root";
$cfg['sql']['pass'] = "";
$cfg['sql']['name'] = "etl_db";

$con = @mysql_connect($cfg['sql']['host'], $cfg['sql']['user'], $cfg['sql']['pass']) or die("<h1>Unable to connect to mySQL Server</h1><br /><h3>Please try again in the near future.</h3><p>If you are seeing this message, it means the mySQL Server or the host is currently offline.<br />Please bear with us as we don't have enough funds to afford a VPS. We are proudly to accept your donations so we can afford a VPS and avoid these messages.<br /><br />~ WiSeXC Staff ~</p><br />");
mysql_select_db($cfg['sql']['name'], $con) or die();
error_reporting(0);
?>
