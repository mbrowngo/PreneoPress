<?php require_once('Connections/preneodb.php'); ?>
<?php

if ( strpos($_SERVER['HTTP_USER_AGENT'], 'Safari') )
{
   $browser = 'Safari';
}
else if ( strpos($_SERVER['HTTP_USER_AGENT'], 'Gecko') )
{
   if ( strpos($_SERVER['HTTP_USER_AGENT'], 'Netscape') )
   {
     $browser = 'Netscape (Gecko/Netscape)';
   }
   else if ( strpos($_SERVER['HTTP_USER_AGENT'], 'Firefox') )
   {
     $browser = 'Mozilla Firefox (Gecko/Firefox)';
   }
   else
   {
     $browser = 'Mozilla (Gecko/Mozilla)';
   }
}
else if ( strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') )
{
   $browser = 'MSIE';
}
else if ( strpos($_SERVER['HTTP_USER_AGENT'], 'Opera') === true)
{
   $browser = 'Opera';
}
else
{
   $browser = 'Other browsers';
}


/* if (!session_id()) session_start();*/
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
$query_getseries = sprintf("SELECT DISTINCT s.series_id, s.series_sort, s.series_name,
(SELECT i.image_filename FROM images i WHERE i.image_series_id = s.series_id ORDER BY i.image_sort ASC LIMIT 1) AS seriesimage FROM series s
INNER JOIN images i
ON i.image_series_id = s.series_id
WHERE s.series_decade_id = %s
ORDER BY s.series_sort ASC", GetSQLValueString($colname_getseries, "int"));
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
if (isset($_GET['sid'])) {
$query_getimages = sprintf("SELECT * FROM images WHERE image_series_id = %s ORDER BY image_sort ASC", GetSQLValueString($colname_getimages, "int"));
} else {
$query_getimages =  sprintf("SELECT * FROM images WHERE image_decade_id = %s ORDER BY RAND()", GetSQLValueString($colname2_getimages, "int"));
}
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
$query_getseriesnext = sprintf("SELECT * FROM series WHERE series_sort > %s AND series_decade_id >= %s ORDER BY series_decade_id, series_sort ASC LIMIT 1", GetSQLValueString($colname_getseriesnext, "int"),GetSQLValueString($colname2_getseriesnext, "int"));
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
if (isset($_GET['sit'])) {
  $colname_getimagesall = $_GET['sit'];
}
$colname2_getimagesall = "-1";
if (isset($_REQUEST['sid'])) {
  $colname2_getimagesall = $_REQUEST['sid'];
}
mysql_select_db($database_preneodb, $preneodb);
$query_getimagesall = sprintf("SELECT * FROM images, series WHERE images.image_series_id = series.series_id AND images.image_sort > %s AND series.series_id = %s ORDER BY images.image_sort", GetSQLValueString($colname_getimagesall, "int"),GetSQLValueString($colname2_getimagesall, "int"));
$getimagesall = mysql_query($query_getimagesall, $preneodb) or die(mysql_error());
$row_getimagesall = mysql_fetch_assoc($getimagesall);
$totalRows_getimagesall = mysql_num_rows($getimagesall);

$colname1_isnextnull = "-1";
if (isset($_GET['sit'])) {
  $colname1_isnextnull = (get_magic_quotes_gpc()) ? $_GET['sit'] : addslashes($_GET['sit']);
}
$colname2_isnextnull = "-1";
if (isset($_GET['sid'])) {
  $colname2_isnextnull = (get_magic_quotes_gpc()) ? $_GET['sid'] : addslashes($_GET['sid']);
}
mysql_select_db($database_preneodb, $preneodb);
$query_isnextnull = sprintf("SELECT * FROM images WHERE image_sort > %s AND image_series_id = %s", GetSQLValueString($colname1_isnextnull, "int"), GetSQLValueString($colname2_isnextnull, "int"));
$isnextnull = mysql_query($query_isnextnull, $preneodb) or die(mysql_error());
$row_isnextnull = mysql_fetch_assoc($isnextnull);
$totalRows_isnextnull = mysql_num_rows($isnextnull);

$colname_getseries = "16";
if (isset($_REQUEST['did'])) {
  $colname_getseries = $_REQUEST['did'];
}
mysql_select_db($database_preneodb, $preneodb);
$query_getseries = sprintf("SELECT DISTINCT s.series_id, s.series_sort, s.series_name, (SELECT i.image_filename FROM images i WHERE i.image_series_id = s.series_id ORDER BY i.image_sort ASC LIMIT 1) AS seriesimage FROM series s INNER JOIN images i ON i.image_series_id = s.series_id WHERE s.series_decade_id = %s ORDER BY s.series_sort ASC", GetSQLValueString($colname_getseries, "int"));
$getseries = mysql_query($query_getseries, $preneodb) or die(mysql_error());
$row_getseries = mysql_fetch_assoc($getseries);
$totalRows_getseries = mysql_num_rows($getseries);

$colname_getseriesimage = "16";
if (isset($_REQUEST['did'])) {
  $colname_getseriesimage = $_REQUEST['did'];
}
mysql_select_db($database_preneodb, $preneodb);
$query_getseriesimage = sprintf("SELECT * FROM images, series WHERE images.image_series_id = series.series_id AND series_decade_id = %s ORDER BY series_sort, image_sort LIMIT 1", GetSQLValueString($colname_getseriesimage, "int"));
$getseriesimage = mysql_query($query_getseriesimage, $preneodb) or die(mysql_error());
$row_getseriesimage = mysql_fetch_assoc($getseriesimage);
$totalRows_getseriesimage = mysql_num_rows($getseriesimage);

$mattvar = "http://www.preneo.org/kent/seriesdetail.php?sst=".$_GET['sst']."&sit=1&sid=".$row_getseriesnext['series_id']."&did=".$_GET['did']."&play=true";

if (($_GET['play'] == "true") && (is_null($row_getimagesall['image_sort']))) { ?>
<script type="text/javascript">
<!--
window.location = "<?php echo($mattvar) ?>";
//-->
</script>
<?php 
}

if (!is_null($_GET['sit'])) {
$mattsit = $_GET['sit'];
} else {
$mattsit = $row_getimages['image_sort'];
}

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<title>Kent Manske</title>
<link href="preneo.css" rel="stylesheet" type="text/css" />
<link href="http://code.jquery.com/mobile/1.3.2/jquery.mobile.structure-1.3.2.min.css" rel="stylesheet" type="text/css" />
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
<style type="text/css">
<!--
.outernav p {
	font-size: 75%;
	font-weight: bold;
	margin-top: 0px;
	margin-bottom: 0px;
	color:#000000;
}
.outernav a:hover {
	color:#bd3535;
	padding-left:1px;
	padding-right:1px;
	padding-top:0px;
	padding-bottom:0px;
	margin-left:-1px;
	font-weight:bold;
}
.clear {
	clear:both;
}
div#sticker {
	padding:20px;
	margin:20px 0;
	background:#AAA;
	width:190px;
}
.stick {
	float:right;
	position:fixed;
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
<?php 
/*
var_dump($query_isnextnull);
var_dump($row_isnextnull);
*/
if (($_GET['play'] == "true") && !is_null($row_getimagesall['image_sort'])) {  ?>
<meta http-equiv="refresh" content="5;URL=http://www.preneo.org/kent/seriesdetail.php?sst=<?php echo $_GET['sst']; ?>&sit=<?php echo $row_getimagesall['image_sort']; ?>&sid=<?php echo $_GET['sid']; ?>&did=<?php echo $_GET['did']; ?>&play=true&matt=true">
<?php }  ?>
<script type="text/javascript">
<!--
function MM_showHideLayers() { //v9.0
  var i,p,v,obj,args=MM_showHideLayers.arguments;
  for (i=0; i<(args.length-2); i+=3) 
  with (document) if (getElementById && ((obj=getElementById(args[i]))!=null)) { v=args[i+2];
    if (obj.style) { obj=obj.style; v=(v=='show')?'visible':(v=='hide')?'hidden':v; }
    obj.visibility=v; }
}
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

var isTouchSupported = 'ontouchstart' in window;
var startEvent = isTouchSupported ? 'touchstart' : 'mousedown';
var moveEvent = isTouchSupported ? 'touchmove' : 'mousemove';
var endEvent = isTouchSupported ? 'touchend' : 'mouseup';


	-->
</script>
</head>
<body>
<script type="text/javascript">
function toTop(id){
document.getElementById(id).scrollTop=0
}

defaultStep=1
step=defaultStep
currentScroll=0;

function scrollDivDown(id){
document.getElementById(id).scrollTop+=step;
currentScroll = document.getElementById(id).scrollTop;
console.log(id);
console.log('current scroll ' + currentScroll);
timerDown=setTimeout("scrollDivDown('"+id+"')",10);
}

function scrollDivUp(id){
document.getElementById(id).scrollTop-=step;
currentScroll = document.getElementById(id).scrollTop;
console.log(id);
console.log('current scroll ' + currentScroll);
timerUp=setTimeout("scrollDivUp('"+id+"')",10);
}

function toBottom(id){
document.getElementById(id).scrollTop=document.getElementById(id).scrollHeight
}

function toPoint(id){
document.getElementById(id).scrollTop=100
}
</script>
<div class="containertop"><a href="http://www.preneo.org/kent"><img src="images/empty.png" width="1000" height="30" /></a></div>
<div class="container" >
	<div class="outernav" id="outernav">
		<div id="leftnav">
			<!-- Beginning of navigation -->
			<!-- Decade List -->
			<div name="decadelistdiv" id="decadelistdiv">
				<p style="font-size:100%;">TIMELINE</p>
				<?php do { ?>
					<p style="font-size:100%;"><a  href="seriesdetail.php?did=<?php echo $row_getdecade['decade_id']; ?>" <?php if ($_GET['did'] == $row_getdecade['decade_id']) { ?>
      style="color:#bd3535; padding-left:1px; padding-right:1px; padding-top:0px; padding-bottom:0px; margin-left:-1px; font-weight:bold;"
      <?php } // Show if recordset empty ?>><?php echo $row_getdecade['decade_name']; ?></a></p>
					<?php } while ($row_getdecade = mysql_fetch_assoc($getdecade)); ?>
				<p style="font-size:100%;"><a class="decadelist" href="about.php?<?php if ($_GET['pageNum_getimages']) { ?>pageNum_getimages=<?php echo $_GET['pageNum_getimages']; ?>&totalRows_getimages=<?php echo $_GET['totalRows_getimages']; ?>&amp;<?php } ?><?php if ($_GET['sid']) { ?>sid=<?php echo $_GET['sid']; ?>&amp;did=<?php echo $_GET['did']; ?>&amp;sst=<?php echo $_GET['sst']; ?><?php } ?>">About Kent</a></p>
			</div>
			<br class="clear" />
			<!-- Series List -->
			<div id="projectNav">
				<p style="font-size:100%;">PROJECT
				<p></p>
				</p>
				<div id="projectNavList">
					<p style="font-size:100%; font-weight:bold; ">
						<?php $row=1; do {  ?>
					<p><a href="seriesdetail.php?sid=<?php echo $row_getseries['series_id']; ?>&amp;sst=<?php echo $row_getseries['series_sort']; ?>&amp;did=<?php echo $_GET['did']; ?>" class="seriesnav seriesButton" style="<?php if (($row_getseries['series_id'] == $_GET['sid']) OR ($_GET['did'] AND !$_GET['sid'] AND $row == 1)) { ?>color:#bd3535; padding-left:1px; padding-right:1px; padding-top:0px; padding-bottom:0px; margin-left:-1px; font-weight:bold;<?php } ?>font-size:130%;" onMouseOver="MM_swapImage('mainimage','','uploads/<?php echo convert_smart_quotes($row_getseries['seriesimage']); ?>',1)" onMouseOut="MM_swapImgRestore()"   ><?php echo convert_smart_quotes($row_getseries['series_name']); ?></a></p>
					<?php $row=$row+1;} while ($row_getseries = mysql_fetch_assoc($getseries)); ?>
					</p>
				</div>
				<p id="projectNavController">
					<button id="downButton" />
					</button>
					&nbsp;&nbsp;
					<button id="upButton" />
					</button>
				</p>
			</div>
		</div>
	</div>
	<!-- End Of Navigation -->
	<div class="imagecontainer2">
		<div id="mainimagecontainer"> <a class="titleToDetailLink" href="imagedetail.php?<?php if ($_GET['pageNum_getimages']) { ?>pageNum_getimages=<?php echo $_GET['pageNum_getimages']; ?>&totalRows_getimages=<?php echo $_GET['totalRows_getimages']; ?>&amp;<?php } ?>sid=<?php echo $_GET['sid']; ?>&amp;did=<?php echo $_GET['did']; ?>&amp;sst=<?php echo $_GET['sst']; ?>&amp;sit=<?php echo $row_getimages['image_sort']; ?>">
			<?php if ((isset($_SESSION["playslides"]) && $_SESSION["playslides"] == "1")) { ?>
			<img src="uploads/<?php echo $row_getimagesall['image_filename']; ?>" id="mainimage" border="0" /></div>
		<?php } else { ?>
		<img  id="test1" src="<?php if (!isset($_GET['sid'])) { ?>uploads/<?php echo convert_smart_quotes($row_getseries['image_filename']); ?><?php } else { ?>uploads/<?php echo $row_getimages['image_filename']; ?><?php } ?>" id="mainimage" border="0" />
		<!--	<div id="titlearea" >
			<p style="font-size:100%;">TITLE</p>
			<p style="font-size:100%;width:220px;">
				<a class="titleToDetailLink" href="imagedetail.php">
					<?php echo $row_getimages['image_title']; ?><br  />
				<span style="color:#000000;font-size:100%;font-weight:normal;"><?php echo $row_getimages['image_year']; ?></span>
				</a>
			</p>
		</div> -->
	</div>
	<?php } ?>
	</a>
	<?php do { ?>
	<div class="repeatmainimage">
	<?php if ($_GET['sid']) { ?>
	<!-- If this is WITH series number -->
	<div class="repeatmainimageimagecontainer"> <a href="imagedetail.php?sid=<?php echo $_GET['sid']; ?>&amp;did=<?php echo $_GET['did']; ?>&amp;iid=<?php echo $row_getimagesall['image_id']; ?>&amp;sst=<?php echo $_GET['sst']; ?>"> <img src="<?php if (!isset($_GET['sid'])) { ?>uploads/<?php echo convert_smart_quotes($row_getseriesimage['image_filename']); ?><?php } else { ?>uploads/<?php echo $row_getimagesall['image_filename']; ?><?php } ?>" id="mainimage" class="mainimage" /></a> </div>
	<?php } else { ?>
	<!-- If this is without series number -->
	<div class="repeatmainimageimagecontainer"> <a href="imagedetail.php?sid=<?php echo $_GET['sid']; ?>&amp;did=<?php echo $_GET['did']; ?>&amp;iid=<?php echo $row_getseriesimage['image_id']; ?>&amp;sst=<?php echo $_GET['sst']; ?>"> <img src="<?php if (!isset($_GET['sid'])) { ?>uploads/<?php echo convert_smart_quotes($row_getseriesimage['image_filename']); ?><?php } else { ?>uploads/<?php echo $row_getseriesimage['image_filename']; ?><?php } ?>" id="mainimage" class="mainimage" /></a> </div>
	<br class="clear" />
	<?php } ?>
	<br class="clear" />
	<div id="titlearea">
		<p style="font-size:100%;width:220px;"> <a class="titleToDetailLink" href="imagedetail.php?sid=<?php echo $_GET['sid']; ?>&amp;did=<?php echo $_GET['did']; ?>&amp;iid=<?php echo $row_getimagesall['image_id']; ?>&amp;sst=<?php echo $_GET['sst']; ?>"> <?php echo $row_getimagesall['image_title']; ?>&nbsp; <span style="color:#000000;font-size:100%;font-weight:normal;"><?php echo $row_getimagesall['image_year']; ?></span> </a> </p>
	</div>
</div>
<?php } while ($row_getimagesall = mysql_fetch_assoc($getimagesall)); ?>
</div>
<br class="clear"/>
<div class="containerbottom"></div>
<script>
	$( ".outernav" ).position({
		my: "right top",
		at: "right top",
		of: ".containertop"
</script>
<script>

function showhidenav(){
	if(document.getElementById('projectNavList').scrollTop == 0){
		document.getElementById("upButton").style.visibility="hidden"
	} else {
		document.getElementById("upButton").style.visibility="visible"
	}
};

if(document.getElementById('projectNavList').offsetHeight > 350){
	document.getElementById("projectNavController").style.display="block"
} else {
	document.getElementById("projectNavController").style.display="none"
}
// console.log(document.getElementById('projectNavList').offsetHeight);


$(document).ready(function() {
    var s = $("#outernav");
    var pos = s.position();  
	var skid = 0;
	skid += <?php echo $_GET['skid']; ?> + 0;
	console.log('skip' + skid);
		
	                 
    $(window).scroll(function() {
        var windowpos = $(window).scrollTop();
        // s.html("Distance from top:" + pos.top + "<br />Scroll position: " + windowpos);
        if (windowpos >= pos.top) {
            s.addClass("stick");
        } else {
            s.removeClass("stick");
        }
    });




	//$(".seriesButton").on("mouseover",function(){
	//	$("#mainimagecontainer").css("display","block");
	//	$(".repeatmainimage").css("display","none");
	//});
	
	
	
	$("#downButton").on(startEvent,function(){
		scrollDivDown('projectNavList');
	});
	
	$("#downButton").on(endEvent,function(){
		clearTimeout(timerDown);
		appendScrollPos();
		showhidenav();
	});
	
	$("#upButton").on(startEvent,function(){
		scrollDivUp('projectNavList');
	});
	
	$("#upButton").on(endEvent,function(){
		clearTimeout(timerUp);
		appendScrollPos();
		showhidenav();
	});

	$(".repeatmainimageimagecontainer, .titleToDetailLink").on(startEvent,function(){
		localStorage.setItem('scrollVal', window.scrollY);
	});
		
	if(localStorage.getItem('scrollVal') != null){
			var valFromStorage = '';
			valFromStorage = localStorage.getItem('scrollVal');
			window.scroll(0,valFromStorage);
	}


	function appendScrollPos(){
		elements = $('.seriesButton');
		elements.each(function(){
			var crispin = $(this).attr("href");
			crispin = crispin + "&skid=" + currentScroll;
			$(this).attr("href",crispin);
		});
	}

	if(skid>20){
		$('#projectNavList').scrollTop(120);
		showhidenav();
	}
	
	appendScrollPos();
});

</script>
</body>
</html>
<?php
mysql_free_result($getdecade);
mysql_free_result($getseries);
mysql_free_result($getseriesimage);
mysql_free_result($getimages);
mysql_free_result($getseriesnext);
mysql_free_result($getseriesprevious);
mysql_free_result($getimagesall);
mysql_free_result($isnextnull);
?>
