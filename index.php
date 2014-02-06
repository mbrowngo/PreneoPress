<?php require_once('Connections/preneodb.php'); ?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

mysql_select_db($database_preneodb, $preneodb);
$query_getdecades = "SELECT * FROM decade ORDER BY decade_name DESC";
$getdecades = mysql_query($query_getdecades, $preneodb) or die(mysql_error());
$row_getdecades = mysql_fetch_assoc($getdecades);
$totalRows_getdecades = mysql_num_rows($getdecades);

mysql_select_db($database_preneodb, $preneodb);
$query_getPreloadImages = "SELECT * FROM images WHERE image_sort <= 12";
$getPreloadImages = mysql_query($query_getPreloadImages, $preneodb) or die(mysql_error());
$row_getPreloadImages = mysql_fetch_assoc($getPreloadImages);
$totalRows_getPreloadImages = mysql_num_rows($getPreloadImages);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Kent Manske</title>

<link href="preneo.css" rel="stylesheet" type="text/css" />

<style type="text/css">
<!--
p {
	font-size: 80%;
	font-weight: bold;
	margin-top: 0px;
	margin-bottom: 0px;
	color:#000000;
}

a:hover {
	color:#bd3535;
	padding-left:1px;
	padding-right:1px;
	padding-top:0px;
	padding-bottom:0px;
	margin-left:-1px;
	font-weight:bold;
}
-->
</style>
<script type="text/javascript">
<!--
function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}
//-->
</script>
</head>

<body onload="MM_preloadImages(<?php do { ?>'<?php echo $row_getPreloadImages['image_filename']; ?>',<?php } while ($row_getPreloadImages = mysql_fetch_assoc($getPreloadImages)); ?>)">
<div id="containerlanding">
<div style="margin-top: 63px; margin-left:27px; float:right; width:206px;">
  <?php do { ?>
    <p><a class="decadelist" href="seriesdetail.php?did=<?php echo $row_getdecades['decade_id']; ?>&amp;sst=1"><?php echo $row_getdecades['decade_name']; ?></a></p>
    <?php } while ($row_getdecades = mysql_fetch_assoc($getdecades)); ?>
    <p><a class="decadelist" href="about.php">About Kent</a></p>
</div>
</div>
</body>
</html>
<?php
mysql_free_result($getdecades);

mysql_free_result($getPreloadImages);
?>
