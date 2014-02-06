<?php require_once('Connections/preneodb.php'); ?>
<?php

/*if (!session_id()) session_start();*/
if ((isset($_GET['play']) && $_GET['play'] == "true"))     {
  $_SESSION["playslides"] = "1";
}
if ((isset($_GET['play']) && $_GET['play'] == "false"))     {
  $_SESSION["playslides"] = "0";
}

function convert_smart_quotes($string) { 
    //converts smart quotes to normal quotes.
   	$search = array(chr(212),chr(213),chr(210),chr(211),chr(209),chr(208),chr(201),chr(145),chr(146),chr(147),chr(148),chr(151),chr(150),chr(133));
	$replace = array('&#8216;','&#8217;','&#8220;','&#8221;','&#8211;','&#8212;','&#8230;','&#8216;','&#8217;','&#8220;','&#8221;','&#8211;','&#8212;','&#8230;');
    return str_replace($search, $replace, $string); 
}

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

$currentPage = $_SERVER["PHP_SELF"];

mysql_select_db($database_preneodb, $preneodb);
$query_getdecade = "SELECT * FROM decade ORDER BY decade_name DESC";
$getdecade = mysql_query($query_getdecade, $preneodb) or die(mysql_error());
$row_getdecade = mysql_fetch_assoc($getdecade);
$totalRows_getdecade = mysql_num_rows($getdecade);

$colname_getseries = "-1";
if (isset($_GET['sid'])) {
  $colname_getseries = (get_magic_quotes_gpc()) ? $_GET['sid'] : addslashes($_GET['sid']);
}
mysql_select_db($database_preneodb, $preneodb);
$query_getseries = sprintf("SELECT * FROM series WHERE series_id = %s ORDER BY series_sort ASC", GetSQLValueString($colname_getseries, "int"));
$getseries = mysql_query($query_getseries, $preneodb) or die(mysql_error());
$row_getseries = mysql_fetch_assoc($getseries);
$totalRows_getseries = mysql_num_rows($getseries);

$maxRows_getimages = 1;
$pageNum_getimages = 0;
if (isset($_GET['pageNum_getimages'])) {
  $pageNum_getimages = $_GET['pageNum_getimages'];
}
$startRow_getimages = $pageNum_getimages * $maxRows_getimages;

$colname_getimages = "68";
if (isset($_GET['sid'])) {
  $colname_getimages = $_GET['sid'];
}
$colname2_getimages = "489";
if (isset($_GET['iid'])) {
  $colname2_getimages = $_GET['iid'];
}
mysql_select_db($database_preneodb, $preneodb);
$query_getimages = sprintf("SELECT * FROM images WHERE image_series_id = %s AND image_id = %s ORDER BY image_sort ASC ", GetSQLValueString($colname_getimages, "int"),GetSQLValueString($colname2_getimages, "int"));
$query_limit_getimages = sprintf("%s LIMIT %d, %d", $query_getimages, $startRow_getimages, $maxRows_getimages);
$getimages = mysql_query($query_limit_getimages, $preneodb) or die(mysql_error());
$row_getimages = mysql_fetch_assoc($getimages);

if (isset($_GET['totalRows_getimages'])) {
  $totalRows_getimages = $_GET['totalRows_getimages'];
} else {
  $all_getimages = mysql_query($query_getimages);
  $totalRows_getimages = mysql_num_rows($all_getimages);
}
$totalPages_getimages = ceil($totalRows_getimages/$maxRows_getimages)-1;

$colname_getseriesnext = "-1";
if (isset($_GET['sst'])) {
  $colname_getseriesnext = (get_magic_quotes_gpc()) ? $_GET['sst'] : addslashes($_GET['sst']);
}
$colname2_getseriesnext = "-1";
if (isset($_GET['did'])) {
  $colname2_getseriesnext = (get_magic_quotes_gpc()) ? $_GET['did'] : addslashes($_GET['did']);
}
mysql_select_db($database_preneodb, $preneodb);
$query_getseriesnext = sprintf("SELECT * FROM series WHERE series_sort > %s AND series_decade_id >= %s ORDER BY series_sort ASC LIMIT 1", GetSQLValueString($colname_getseriesnext, "int"),GetSQLValueString($colname2_getseriesnext, "int"));
$getseriesnext = mysql_query($query_getseriesnext, $preneodb) or die(mysql_error());
$row_getseriesnext = mysql_fetch_assoc($getseriesnext);
$totalRows_getseriesnext = mysql_num_rows($getseriesnext);

$colname_getseriesprevious = "-1";
if (isset($_GET['sst'])) {
  $colname_getseriesprevious = (get_magic_quotes_gpc()) ? $_GET['sst'] : addslashes($_GET['sst']);
}
$colname2_getseriesprevious = "-1";
if (isset($_GET['did'])) {
  $colname2_getseriesprevious = (get_magic_quotes_gpc()) ? $_GET['did'] : addslashes($_GET['did']);
}
mysql_select_db($database_preneodb, $preneodb);
$query_getseriesprevious = sprintf("SELECT * FROM series WHERE series_sort < %s  AND series_decade_id = %s ORDER BY series_sort DESC LIMIT 1", GetSQLValueString($colname_getseriesprevious, "int"),GetSQLValueString($colname2_getseriesprevious, "int"));
$getseriesprevious = mysql_query($query_getseriesprevious, $preneodb) or die(mysql_error());
$row_getseriesprevious = mysql_fetch_assoc($getseriesprevious);
$totalRows_getseriesprevious = mysql_num_rows($getseriesprevious);


$queryString_getimages = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_getimages") == false && 
        stristr($param, "totalRows_getimages") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_getimages = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_getimages = sprintf("&totalRows_getimages=%d%s", $totalRows_getimages, $queryString_getimages);

$queryString_getseries = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_getseries") == false && 
        stristr($param, "totalRows_getseries") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_getseries = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_getseries = sprintf("&totalRows_getseries=%d%s", $totalRows_getseries, $queryString_getseries);


$colname_getimagesall = "-1";
if (isset($_GET['pid'])) {
  $colname_getimagesall = (get_magic_quotes_gpc()) ? $_GET['pid'] : addslashes($_GET['pid']);
}
mysql_select_db($database_preneodb, $preneodb);
$query_getimagesall = sprintf("SELECT * FROM images, series WHERE images.image_series_id = series.series_id AND images.image_id > %s ORDER BY image_decade_id ASC, series_sort, image_sort LIMIT 1", GetSQLValueString($colname_getimagesall, "int"));
$getimagesall = mysql_query($query_getimagesall, $preneodb) or die(mysql_error());
$row_getimagesall = mysql_fetch_assoc($getimagesall);
$totalRows_getimagesall = mysql_num_rows($getimagesall);

?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<title>Kent Manske</title>
<link href="preneo.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
p {
	font-size: 100%;
	margin-top: 12px;
	margin-bottom: 12px;
	color:#000000;
}


-->
</style>
<!--[if IE]>
<style type="text/css">
.containerbottom {
	margin-top:0em;
}
</style>
<![endif]-->
<?php if ((isset($_SESSION["playslides"]) && $_SESSION["playslides"] == "1")) { ?>
<meta http-equiv="refresh" content="4;http://www.preneo.org/kent/seriesdetail.php?sst=<?php echo $_GET['sst']; ?>&amp;sid=<?php echo $_GET['sid']; ?>&amp;did=<?php echo $_GET['did']; ?>&amp;pid=<?php echo $row_getimagesall['image_id']; ?>">
<?php } ?>
</head>
<body>
<div class="containertop"><a href="http://www.preneo.org/kent"><img src="images/empty.png" width="1000" height="30" /></a></div>
<div class="container">
<div class="imagecontainer2" style="display:block;">
 <div id="imagedetailmainimagecontainer">
    <a href="seriesdetail.php?<?php if ($_GET['pageNum_getimages']) { ?>pageNum_getimages=<?php echo $_GET['pageNum_getimages']; ?>&totalRows_getimages=<?php echo $_GET['totalRows_getimages']; ?>&amp;<?php } ?>sid=<?php echo $_GET['sid']; ?>&amp;did=<?php echo $_GET['did']; ?>&amp;sst=<?php echo $_GET['sst']; ?>" style="cursor:default">
	<img src="uploads/<?php echo $row_getimages['image_filename']; ?>" id="mainimage" /></div></a></div>
<div id="leftnav">
<div id="leftnavImageDetailInner">
  <p style="font-size:120%;"><a href="seriesdetail.php?<?php if ($_GET['pageNum_getimages']) { ?>pageNum_getimages=<?php echo $_GET['pageNum_getimages']; ?>&amp;totalRows_getimages=<?php echo $_GET['totalRows_getimages']; ?>&amp;<?php } ?>sid=<?php echo $_GET['sid']; ?>&amp;did=<?php echo $_GET['did']; ?>&amp;sst=<?php echo $_GET['sst']; ?>"><img src="images/navbackover.jpg" border="0" /><!--</a><a href="seriesdetail.php?<?php if ($_GET['pageNum_getimages']) { ?>pageNum_getimages=<?php echo $_GET['pageNum_getimages']; ?>&totalRows_getimages=<?php echo $_GET['totalRows_getimages']; ?>&amp;<?php } ?>sid=<?php echo $_GET['sid']; ?>&amp;did=<?php echo $_GET['did']; ?>&amp;sst=<?php echo $_GET['sst']; ?>">--><img src="images/navforwardover.jpg" border="0" /></a><!--<a href="seriesdetail.php?<?php if ($_GET['pageNum_getimages']) { ?>pageNum_getimages=<?php echo $_GET['pageNum_getimages']; ?>&totalRows_getimages=<?php echo $_GET['totalRows_getimages']; ?>&amp;<?php } ?>sid=<?php echo $_GET['sid']; ?>&amp;did=<?php echo $_GET['did']; ?>&amp;sst=<?php echo $_GET['sst']; ?>"><img src="images/navinfo.jpg" border="0" /></a>-->
</p>
</div> 
</div>
<?php  if ($row_getimages['image_sort'] >= 10) { ?>
<div id="maininfocontainer" style="margin-bottom:3em">
  <table style="font-size:90%; margin-top:-1px;  color:#000;width:90%;" cellpadding="-1" cellspacing="-1">
    <tr align="left" valign="top">
      <td style="font-weight:bold;" colspan="2"><?php echo convert_smart_quotes($row_getimages['image_title']); ?></td>
    </tr>
    <tr align="left" valign="top">
      <td width="75">&nbsp;</td>
      <td width="500">&nbsp;</td>
    </tr>
    <tr align="left" valign="top">
      <td>series:</td>
      <td><?php echo convert_smart_quotes($row_getseries['series_name']); ?></td>
    </tr>
    <tr align="left" valign="top">
      <td>media:</td>
      <td><?php echo convert_smart_quotes($row_getimages['image_media']); ?></td>
    </tr>
    <tr align="left" valign="top">
      <td>size:</td>
      <td><?php echo convert_smart_quotes($row_getimages['image_size']); ?></td>
    </tr>
    <tr align="left" valign="top">
      <td>edition:</td>
      <td><?php echo convert_smart_quotes($row_getimages['image_edition']); ?></td>
    </tr>
    <tr align="left" valign="top">
      <td>date:</td>
      <td><?php echo convert_smart_quotes($row_getimages['image_year']); ?></td>
    </tr>
    <tr align="left" valign="top">
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr align="left" valign="top">
      <td colspan="2" style="font-weight:normal;"><?php echo $row_getimages['image_about']; ?></td>
    </tr>
  </table>
  <div class="backNavButton"><a href="seriesdetail.php?sid=<?php echo $_REQUEST['sid']; ?>&did=<?php echo $_REQUEST['did']; ?>&sst=<?php echo $_REQUEST['sst']; ?>&amp;skid=<?php echo $_GET['skid']; ?>"><img id="backButton" src="images/newnavback.jpg"  /></a></div>
</div>
<?php } else { ?>
<div id="maininfocontainer" ">
  <table style="font-size:90%; margin-top:-1px; margin-bottom:-1px; color:#000;width:90%;" cellpadding="-1" cellspacing="-1">
    <tr align="left" valign="top">
      <td width="50" style="font-size:120%;font-weight:bold;" colspan="2"><?php echo convert_smart_quotes($row_getseries['series_name']); ?><br /><span style="font-weight:normal;font-size:80%"><?php echo convert_smart_quotes($row_getseries['series_tagline']); ?></span></td>
    </tr>
        <tr align="left" valign="top">
      <td width="50">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr align="left" valign="top">
      <td width="50" style="font-weight:normal;" colspan="2">
</td>
    </tr>
    <tr align="left" valign="top">
      <td style="font-weight:bold;">media:</td>
      <td><?php echo convert_smart_quotes($row_getseries['series_media']); ?></td>
    </tr>
    <tr align="left" valign="top">
      <td style="font-weight:bold;">edition:</td>
      <td><?php echo convert_smart_quotes($row_getseries['series_edition']); ?></td>
    </tr>
    <tr align="left" valign="top">
      <td style="font-weight:bold;">size:</td>
      <td><?php echo convert_smart_quotes($row_getseries['series_size']); ?></td>
    </tr>
    <tr align="left" valign="top">
      <td style="font-weight:bold;">date:</td>
      <td><?php echo convert_smart_quotes($row_getseries['series_date']); ?></td>
    </tr>
    <?php if ($row_getseries['series_head1'] <> '') { ?>
    <tr align="left" valign="top">
      <td><?php echo convert_smart_quotes($row_getseries['series_head1']); ?></td>
      <td><?php echo convert_smart_quotes($row_getseries['series_body1']); ?></td>
    </tr>
    <?php } ?>
    <?php if ($row_getseries['series_head2'] <> '') { ?>
    <tr align="left" valign="top">
      <td><?php echo convert_smart_quotes($row_getseries['series_head2']); ?></td>
      <td><?php echo convert_smart_quotes($row_getseries['series_body2']); ?></td>
    </tr>
    <?php } ?>
    <?php if ($row_getseries['series_head3'] <> '') { ?>
    <tr align="left" valign="top">
      <td><?php echo convert_smart_quotes($row_getseries['series_head3']); ?></td>
      <td><?php echo convert_smart_quotes($row_getseries['series_body3']); ?></td>
    </tr>
    <?php } ?>
    <tr align="left" valign="top">
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr align="left" valign="top">
      <td colspan="2" style="font-weight:normal;"><?php echo $row_getseries['series_about']; ?></td>
    </tr>
  </table>
  <div class="backNavButton"><a href="seriesdetail.php?sid=<?php echo $_REQUEST['sid']; ?>&did=<?php echo $_REQUEST['did']; ?>&sst=<?php echo $_REQUEST['sst']; ?>"><img id="backButton" src="images/newnavback.jpg"  /></a></div>
</div>
<?php } ?>


<br class="clear"/>

<div class="containerbottom"></div>
</body>
</html>
<?php
mysql_free_result($getdecade);
mysql_free_result($getseries);
mysql_free_result($getimages);
mysql_free_result($getseriesnext);
mysql_free_result($getseriesprevious);
mysql_free_result($getimagesall);
?>

