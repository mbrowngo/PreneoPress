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

function convert_smart_quotes($string) { 
    //converts smart quotes to normal quotes.
   	$search = array(chr(212),chr(213),chr(210),chr(211),chr(209),chr(208),chr(201),chr(145),chr(146),chr(147),chr(148),chr(151),chr(150),chr(133));
	$replace = array('&#8216;','&#8217;','&#8220;','&#8221;','&#8211;','&#8212;','&#8230;','&#8216;','&#8217;','&#8220;','&#8221;','&#8211;','&#8212;','&#8230;');
    return str_replace($search, $replace, $string); 
}

mysql_select_db($database_preneodb, $preneodb);
$query_getdecade = "SELECT * FROM decade ORDER BY decade_name DESC";
$getdecade = mysql_query($query_getdecade, $preneodb) or die(mysql_error());
$row_getdecade = mysql_fetch_assoc($getdecade);
$totalRows_getdecade = mysql_num_rows($getdecade);

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
mysql_select_db($database_preneodb, $preneodb);
$query_getimages = sprintf("SELECT * FROM images WHERE image_series_id = %s", GetSQLValueString($_REQUEST['sid'], "int"));
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

$colname_getseriesimage = "-1";
if (isset($_GET['sid'])) {
  $colname_getseriesimage = (get_magic_quotes_gpc()) ? $_GET['sid'] : addslashes($_GET['sid']);
}
mysql_select_db($database_preneodb, $preneodb);
$query_getseriesimage = sprintf("SELECT * FROM images WHERE image_series_id = %s AND image_navigational = 1", GetSQLValueString($colname_getseriesimage, "int"));
$getseriesimage = mysql_query($query_getseriesimage, $preneodb) or die(mysql_error());
$row_getseriesimage = mysql_fetch_assoc($getseriesimage);
$totalRows_getseriesimage = mysql_num_rows($getseriesimage);

$colname_getselecteddecade = "-1";
if (isset($_GET['did'])) {
  $colname_getselecteddecade = (get_magic_quotes_gpc()) ? $_GET['did'] : addslashes($_GET['did']);
}
mysql_select_db($database_preneodb, $preneodb);
$query_getselecteddecade = sprintf("SELECT * FROM decade WHERE decade_id = %s", GetSQLValueString($colname_getselecteddecade, "int"));
$getselecteddecade = mysql_query($query_getselecteddecade, $preneodb) or die(mysql_error());
$row_getselecteddecade = mysql_fetch_assoc($getselecteddecade);
$totalRows_getselecteddecade = mysql_num_rows($getselecteddecade);

$colname_getseries = "-1";
if (isset($_GET['did'])) {
  $colname_getseries = (get_magic_quotes_gpc()) ? $_GET['did'] : addslashes($_GET['did']);
}
mysql_select_db($database_preneodb, $preneodb);
$query_getseries = sprintf("SELECT * FROM series, images WHERE series.series_decade_id = %s AND series.series_id = images.image_series_id", GetSQLValueString($colname_getseries, "int"));
$getseries = mysql_query($query_getseries, $preneodb) or die(mysql_error());
$row_getseries = mysql_fetch_assoc($getseries);
$totalRows_getseries = mysql_num_rows($getseries);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Kent Manske</title>

<link href="preneo.css" rel="stylesheet" type="text/css" />

<style type="text/css">
<!--
p {
	font-size:75%;
	font-weight: bold;
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

<script type="text/javascript">
<!--
function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}
function MM_swapImgRestore() { //v3.0
  var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
}

function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function MM_swapImage() { //v3.0
  var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
   if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
}
//-->

</script>
</head>
<body>
<div class="containertop"><a href="http://www.preneo.org/kent"><img src="images/empty.png" width="1000" height="30" /></a></div>
<div class="container">
<div class="imagecontainer2">
	<img name="thumbimage" border="0" height="720" width="720"/></div>
<!--  Begin Decade List -->
<div style="margin-left:26px; float:left; width:200px; margin-top:3em; height:730px">
<p style="font-weight:bold;">TIMELINE</p>
  

<p><a class="decadelist" <?php if ($_GET['did'] == $row_getdecade['decade_id']) { ?>
      style="color:#ff0000; padding-left:1px; padding-right:1px; padding-top:0px; padding-bottom:0px; margin-left:-1px;"
      <?php } // Show if recordset empty ?>></a></p>
<p><a class="decadelist" href="about.php">About Kent</a></p>

<div style="margin-top:3em;">
<p style="font-weight:bold;">PROJECTS</p>

<p><a ?>" class="seriesnav" style="
      <?php if ($row_getseries['series_id'] == $_GET['sid'] ) { ?>
  color:#ff0000; padding-left:1px; padding-right:1px; padding-top:0px; padding-bottom:0px; margin-left:-1px; font-weight:bold;
  <?php } ?>
  " ></a> <span name="viewnote" style="color:#ffffff; font-weight:100;">&nbsp;View Project</span></p>
</div>
</div>
<br class="clear" />
<!--  End Series List -->

<div class="containerbottom"></div>
</body>
</html>
<?php
mysql_free_result($getdecade);
mysql_free_result($getimages);
mysql_free_result($getseriesimage);
mysql_free_result($getselecteddecade);
mysql_free_result($getseries);
?>
