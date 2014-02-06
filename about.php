<?php require_once('Connections/preneodb.php'); ?>
<?php

/* if (!session_id()) session_start(); */
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
if (isset($_GET['did'])) {
  $colname_getseries = (get_magic_quotes_gpc()) ? $_GET['did'] : addslashes($_GET['did']);
}
mysql_select_db($database_preneodb, $preneodb);
$query_getseries = sprintf("SELECT * FROM series WHERE series_decade_id = %s ORDER BY series_sort ASC", GetSQLValueString($colname_getseries, "int"));
$getseries = mysql_query($query_getseries, $preneodb) or die(mysql_error());
$row_getseries = mysql_fetch_assoc($getseries);
$totalRows_getseries = mysql_num_rows($getseries);

$maxRows_getimages = 1;
$pageNum_getimages = 0;
if (isset($_GET['pageNum_getimages'])) {
  $pageNum_getimages = $_GET['pageNum_getimages'];
}
$startRow_getimages = $pageNum_getimages * $maxRows_getimages;

$colname_getimages = "-1";
if (isset($_GET['sid'])) {
  $colname_getimages = (get_magic_quotes_gpc()) ? $_GET['sid'] : addslashes($_GET['sid']);
}
$colname2_getimages = "-1";
if (isset($_GET['did'])) {
  $colname2_getimages = (get_magic_quotes_gpc()) ? $_GET['did'] : addslashes($_GET['did']);
}
mysql_select_db($database_preneodb, $preneodb);
$query_getimages = sprintf("SELECT * FROM images WHERE image_series_id = %s AND image_decade_id = %s", GetSQLValueString($colname_getimages, "int"),GetSQLValueString($colname2_getimages, "int"));
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

mysql_select_db($database_preneodb, $preneodb);
$query_getArtist = "SELECT * FROM artist";
$getArtist = mysql_query($query_getArtist, $preneodb) or die(mysql_error());
$row_getArtist = mysql_fetch_assoc($getArtist);
$totalRows_getArtist = mysql_num_rows($getArtist);

if ($_REQUEST['id']) {
$headvar = "user_Head".$_REQUEST['id'];
$bodyvar = "user_Body".$_REQUEST['id'];
} else {
$headvar = "user_Head1";
$bodyvar = "user_Body1";
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Kent Manske</title>
<link href="preneo.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
p {
	font-size: 100%;
	margin-top: 0px;
	margin-bottom: 0px;
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
</head>
<body>
<div class="containertop"><a href="http://www.preneo.org/kent"><img src="images/empty.png" width="1000" height="30" /></a></div>
<div class="container">
<div name="decadelistdiv" style="margin-top: 21px; float:right; width:206px; font-size:80%">
  <p style="font-size:100%;font-weight:bold;">TIMELINE</p>
  <?php do { ?>
    <p style="font-size:100%;"><a class="decadelist" href="seriesdetail.php?did=<?php echo $row_getdecade['decade_id']; ?>&amp;sst=1"><?php echo $row_getdecade['decade_name']; ?></a></p>
    <?php } while ($row_getdecade = mysql_fetch_assoc($getdecade)); ?>
  <?php if ($_GET['sid']) { ?>
  <p style="font-size:100%;"> <a href="seriesdetail.php?<?php if ($_GET['pageNum_getimages']) { ?>pageNum_getimages=<?php echo $_GET['pageNum_getimages']; ?>&amp;totalRows_getimages=<?php echo $_GET['totalRows_getimages']; ?>&amp;<?php } ?><?php if ($_GET['sid']) { ?>sid=<?php echo $_GET['sid']; ?>&amp;did=<?php echo $_GET['did']; ?>&amp;sst=<?php echo $_GET['sst']; ?><?php } ?>" style="color:#bd3535; padding-left:1px; padding-right:1px; padding-top:0px; padding-bottom:0px; margin-left:-1px; font-weight:bold;">About Kent</a></p>
  <?php } else { ?>
  <p style="font-size:100%;"> <a href="about.php" style="color:#bd3535; padding-left:1px; padding-right:1px; padding-top:0px; padding-bottom:0px; margin-left:-1px; font-weight:bold;">About Kent</a></p>
  <?php } ?>
</div>

<style>
a:hover{color:#bd3535; padding-left:1px; padding-right:1px; padding-top:0px; padding-bottom:0px; margin-left:-1px; font-weight:bold;}
</style>

<div style="margin-top:149px; margin-right:-206px; float:right; width:206px; font-size:80%; ">
  <p style="font-size:100%;font-weight:bold;" >ABOUT KENT</p>
      <p><a href="about.php?id=1" style="<?php if($_REQUEST['id'] == 1 || !isset($_REQUEST['id'])) { ?>color:#bd3535; padding-left:1px; padding-right:1px; padding-top:0px; padding-bottom:0px; margin-left:-1px; font-weight:bold;<?php } ?>"><?php echo convert_smart_quotes($row_getArtist['user_Head1']); ?></a></p>
      <p><a href="about.php?id=2" style="<?php if($_REQUEST['id'] == 2) { ?>color:#bd3535; padding-left:1px; padding-right:1px; padding-top:0px; padding-bottom:0px; margin-left:-1px; font-weight:bold;<?php } ?>"><?php echo convert_smart_quotes($row_getArtist['user_Head2']); ?></a></p>
      <p><a href="about.php?id=3" style="<?php if($_REQUEST['id'] == 3) { ?>color:#bd3535; padding-left:1px; padding-right:1px; padding-top:0px; padding-bottom:0px; margin-left:-1px; font-weight:bold;<?php } ?>"><?php echo convert_smart_quotes($row_getArtist['user_Head3']); ?></a></p>
      <p><a href="about.php?id=4" style="<?php if($_REQUEST['id'] == 4) { ?>color:#bd3535; padding-left:1px; padding-right:1px; padding-top:0px; padding-bottom:0px; margin-left:-1px; font-weight:bold;<?php } ?>"><?php echo convert_smart_quotes($row_getArtist['user_Head4']); ?></a></p>
      <p><a href="about.php?id=5" style="<?php if($_REQUEST['id'] == 5) { ?>color:#bd3535; padding-left:1px; padding-right:1px; padding-top:0px; padding-bottom:0px; margin-left:-1px; font-weight:bold;<?php } ?>"><?php echo convert_smart_quotes($row_getArtist['user_Head5']); ?></a></p>
      <p><a href="about.php?id=6" style="<?php if($_REQUEST['id'] == 6) { ?>color:#bd3535; padding-left:1px; padding-right:1px; padding-top:0px; padding-bottom:0px; margin-left:-1px; font-weight:bold;<?php } ?>"><?php echo convert_smart_quotes($row_getArtist['user_Head6']); ?></a></p>
      <p><a href="about.php?id=7" style="<?php if($_REQUEST['id'] == 7) { ?>color:#bd3535; padding-left:1px; padding-right:1px; padding-top:0px; padding-bottom:0px; margin-left:-1px; font-weight:bold;<?php } ?>"><?php echo convert_smart_quotes($row_getArtist['user_Head7']); ?></a></p>
      <p><a href="about.php?id=8" style="<?php if($_REQUEST['id'] == 8) { ?>color:#bd3535; padding-left:1px; padding-right:1px; padding-top:0px; padding-bottom:0px; margin-left:-1px; font-weight:bold;<?php } ?>"><?php echo convert_smart_quotes($row_getArtist['user_Head8']); ?></a></p>
      <p><a href="about.php?id=9" style="<?php if($_REQUEST['id'] == 9) { ?>color:#bd3535; padding-left:1px; padding-right:1px; padding-top:0px; padding-bottom:0px; margin-left:-1px; font-weight:bold;<?php } ?>"><?php echo convert_smart_quotes($row_getArtist['user_Head9']); ?></a></p>
      <p><a href="about.php?id=10" style="<?php if($_REQUEST['id'] == 10) { ?>color:#bd3535; padding-left:1px; padding-right:1px; padding-top:0px; padding-bottom:0px; margin-left:-1px; font-weight:bold;<?php } ?>"><?php echo convert_smart_quotes($row_getArtist['user_Head10']); ?></a></p>
      <p><a href="about.php?id=11" style="<?php if($_REQUEST['id'] == 11) { ?>color:#bd3535; padding-left:1px; padding-right:1px; padding-top:0px; padding-bottom:0px; margin-left:-1px; font-weight:bold;<?php } ?>"><?php echo convert_smart_quotes($row_getArtist['user_Head11']); ?></a></p>
      <p><a href="about.php?id=12" style="<?php if($_REQUEST['id'] == 12) { ?>color:#bd3535; padding-left:1px; padding-right:1px; padding-top:0px; padding-bottom:0px; margin-left:-1px; font-weight:bold;<?php } ?>">Site Info</a></p>
</div>



<div id="maininfocontainer" style="float:left;margin-top:19px;margin-left:60px; width:719px; min-height:605px;">
  <table style=" margin-top:-1px; margin-bottom:-1px; color:#000;width:90%;font-size:90%;" cellpadding="-1" cellspacing="-1">
    <tr align="left" valign="top">
      <td style="font-weight:bold;" class="aboutitems">
	  <?php if ($_REQUEST['id'] == 12) { ?>
      Site Info<br /><br />
	  <?php } else { ?>
	  <?php echo convert_smart_quotes($row_getArtist[$headvar]); ?><br />
        <br />
        <?php } ?>
      </td>
    </tr>
    <tr>
      <td style="font-weight:normal;"  class="aboutitems">
	  <?php if ($_REQUEST['id'] == 12) { ?>
      <p class="aboutitems"><strong>Site Design & Production</strong><br />
This site was designed by Kent Manske in collaboration with Matt Brown.  Web development by Matt Brown at <a href="http://mossbeachdevelopment.com/" target="_blank">Moss Beach Development </a>
who is also a ceramist specializing in Japanese and Korean style ceramics at <a href="http://www.mossbeachceramics.com/" target="_blank">Moss Beach Ceramics </a> and my friend.</p>
<!--<p>&nbsp;</p>

<p class="aboutitems"><strong>Navigation:</strong><br>
This site is organized by Projects.  View works in a project by single click forward/back or by continuous running slide show. Slide Show exhibits work continuously until end of the decade.</p>
<p>&nbsp;</p>
<p><img src="images/navigation_instruction.gif" /></p>
		<p class="aboutitems"><strong>Page Zoom:</strong><br>
		<p>You can zoom in and out of web pages including images with the following keyboard shortcuts:</p>
		<ul>
			<li>Press command++ to display the web page one size larger.</li>
			<li>Press command+- to display the web page one size smaller.</li>
			<li>Press command+0 (zero) to display the web page at its normal size.</li>
			</ul
	  >--><?php } else { ?>
	  <?php echo convert_smart_quotes($row_getArtist[$bodyvar]); ?><br />
        <br />
        <?php } ?>
</td>
  </table>
</div>
<br class="clear"/>
<!-- Decade List -->
<!-- Series List -->

<div style="margin-top: -225px; margin-left:26px; float:left; width:300px; color:#990033;  font-weight:bold;"> &nbsp; </div>
<br class="clear" />
<div style="margin-top: -110px; margin-left:26px; float:left; width:300px; color:#990033;  font-weight:bold;">
  <p style="font-size:120%;"> &nbsp; 
</div>
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

mysql_free_result($getArtist);
?>
