<?php
header('Content-Type: text/html');

set_time_limit(20);
error_reporting(0);

$xml=$_GET["x"];
if ($xml=="") exit;

$width=$_GET["w"];
$height=$_GET["h"];
$border_color=$_GET["bc"];
$border_width=$_GET["bw"];
$background_color=$_GET["bgc"];
$maxitems=$_GET["m"];

$include_title=$_GET["it"];
$title=$_GET["t"];
$title_color=$_GET["tc"];
$title_size=$_GET["ts"];
$title_background=$_GET["tb"];

$include_link=$_GET["il"];
$link_color=$_GET["lc"];
$link_size=$_GET["ls"];
$link_bold=$_GET["lb"];

$include_description=$_GET["id"];
$description_color=$_GET["dc"];
$description_size=$_GET["ds"];

$include_date=$_GET["idt"];
$date_color=$_GET["dtc"];
$date_size=$_GET["dts"];

$title_style="color:#".$title_color."; font-size:".$title_size."px;";
$title_th_style="";
if ($title_background!="" && $title_background!="transparent")
	$title_th_style="background-color:#".$title_background;

$item_style="color:#".$link_color."; font-size:".$link_size."px;";
if ($link_bold=="true")
	$item_style=$item_style." font-weight:bold;";
$date_style="color:#".$date_color."; font-size:".$date_size."px;";
$description_style="color:#".$description_color."; font-size:".$description_size."px;";

$xmlDoc = new DOMDocument();
if (!$xmlDoc->load($xml, LIBXML_NOERROR))
{
	echo 'Failed to open the RSS feed.';
	exit;
}

$channel=$xmlDoc->getElementsByTagName('channel')->item(0);
$channel_title = $channel->getElementsByTagName('title')
->item(0)->childNodes->item(0)->nodeValue;
$channel_link = $channel->getElementsByTagName('link')
->item(0)->childNodes->item(0)->nodeValue;
$channel_desc = $channel->getElementsByTagName('description')
->item(0)->childNodes->item(0)->nodeValue;

if ($title!="" && $title!="(default)")
	$channel_title=$title;

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?=$xmlDoc->actualEncoding ?>" />
   <style type = "text/css">
      html {border: 0; padding:0; margin:0;}
   </style>
</head>

<body style = "border:0; padding:0; margin:0; overflow-y:auto; overflow-x:hidden; <?=($background_color!="transparent" ? "background:#".$background_color : "")?>">
<table border="0" cellpadding="2" cellspacing="2" width="100%" style="table-layout:fixed;margin:0;padding:0;">

<?php
	if ($include_title!="false")
	{
?>
<tr><th style="<?=$title_th_style ?>"><span style="<?=$title_style ?>"><?=$channel_title ?></span></th></tr>
<?php
	}
?>

<?php
$x=$xmlDoc->getElementsByTagName('item');

$n=$x->length;
if ($maxitems<$n)
	$n=$maxitems;

for ($i=0; $i<$n; $i++)
  {
  $item_title=$x->item($i)->getElementsByTagName('title')->item(0)->childNodes->item(0)->nodeValue;
  $item_link=$x->item($i)->getElementsByTagName('link')->item(0)->childNodes->item(0)->nodeValue;
  $item_desc=$item_title;
  if ($x->item($i)->getElementsByTagName('description') != NULL)
	if ($x->item($i)->getElementsByTagName('description')->item(0)->childNodes != NULL)
  $item_desc=$x->item($i)->getElementsByTagName('description')->item(0)->childNodes->item(0)->nodeValue;
  
  $item_date=$x->item($i)->getElementsByTagName('pubDate')->item(0)->childNodes->item(0)->nodeValue;

  if ($item_title=="")
	break;
	
	$item_desc=str_replace('&#60;br clear="all"/>','',$item_desc);
	$item_desc=str_replace('&#60;br clear="both"/>','',$item_desc);

	$item_desc=str_replace('<br clear="all"/>','',$item_desc);
	$item_desc=str_replace('<br clear="both"/>','',$item_desc);
	
	$item_desc=str_replace('<br clear="all" />','',$item_desc);
	$item_desc=str_replace('<br clear="both" />','',$item_desc);

	$item_desc=str_replace('<br style="clear: both;"/>','',$item_desc);
	$item_desc=str_replace('<br style="clear: all;"/>','',$item_desc);

	$item_desc=str_replace('<br style="clear: both;" />','',$item_desc);
	$item_desc=str_replace('<br style="clear: all;" />','',$item_desc);

	$item_desc=str_replace('<br clear="both" style="clear: both;"/>','',$item_desc);
	$item_desc=str_replace('<br clear="all" style="clear: all;"/>','',$item_desc);

	$item_desc=str_replace('<a ','<a target="_blank" ',$item_desc);


	if ($include_link=="true")
	{
?>
<tr><td><a href="<?=$item_link ?>" target="_blank" style="<?=$item_style ?>"><?=$item_title ?></a></td></tr>
<?php
	}
	if ($include_date=="true")
	{
		$item_date_str = $item_date;
?>
<tr><td><span style="<?=$date_style ?>"><?=$item_date_str ?></span></td></tr>
<?php
	}
	if ($include_description=="true")
	{
?>
<tr><td><span style="<?=$description_style ?>"><?=$item_desc ?></span></td></tr>
<?php
	}
  }
?>
<tr><td><br></td></tr>
<tr><td align="right" style="font-weight:none"><small>rssfeedwidget.com</small></td></tr>
</table>
</body>

</html>
