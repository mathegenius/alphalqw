<?php
/** Loader Filename and Path **/
$sFile = "http://".$_SERVER['SERVER_NAME']."/game/gamefiles/DarknessBLoOd.swf";

$size = $_GET["size"];

/** Default Width and Height **/
$width = "960";
$height = "550";

/** Check Size **/
switch($size) {
	default:
		break;
	case 'large':
		$width = "1185";
		$height = "679";
		break;
	case 'huge':
		$width = "1792";
		$height = "1027";
		break;
	case 'tiny':
		$width = "706";
		$height = "405";
		break;
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Play | WiSeXC</title>
<link rel="shortcut icon" href="/ico/favicon.ico" />
<link href="text.css" rel="stylesheet" type="text/css" media="all" />
<link href="../css/black.css" rel="stylesheet" type="text/css" media="all" />
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/swfobject/2/swfobject.js"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1/jquery-ui.min.js"></script>
<script type="text/javascript" src="components.js"></script>
<style type="text/css">!-- .floater{visibility:hidden;width:100%;height:100%;position:absolute;z-index:2;float:none;top:0;left:0;font-size:12px}.main{visibility:visible}body,td,th{color:#fff;font-family:Arial,Helvetica,sans-serif;font-size:12px}body{background:#fff url(/images/skins/skin-epicduel-winter.jpg) fixed no-repeat top center}.black-background{background:#fff}#skin-wrap{width:100%}#right-content{display:block;float:right;width:10%;height:100%;overflow:hidden}#main-content{display:block;width:950px;margin:0 auto 0 auto}.link-hm{width:100%;height:100%;display:block}.pinkText,.greenText{color:#ffffff!important}.whiteText{color:#ffffff!important}.greenText1{color:#ffffff!important}.pinkText1{color:#ffffff!important}</style>
</head>
<body>
<div id="fb-root"></div>
<script>/*<![CDATA[*/(function(e,a,f){var c,b=e.getElementsByTagName(a)[0];if(e.getElementById(f)){return}c=e.createElement(a);c.id=f;c.src="//connect.facebook.net/en_US/all.js#xfbml=1&appId=468230926614503";b.parentNode.insertBefore(c,b)}(document,"script","facebook-jssdk"));/*]]>*/</script>
<div class="fb-like-box" data-href="http://www.facebook.com/aqwps/" data-colorscheme="light" data-show-faces="true" data-header="true" data-stream="false" data-show-border="true"></div>
<div align="center"><strong><br />
<div align="center"><strong><br />
<div align="center"><strong><br />
<div align="center"><strong><br />
<div align="center">
<table width="<?php $width ?> border="4" cellspacing="0" cellpadding="0" id="aqw">
<tr>
<td align="right">
<div id="flashContent" name="flashContent">
<h1>You need at least Flash Player 9.0 to view this page.</h1>
<p><a href="http://www.adobe.com/go/getflashplayer"><img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Get Adobe Flash player" /></a></p>
</div>
<script type="text/javascript">/*<![CDATA[*/var params={LOOP:"false",SCALE:"exactfit",allowScriptAccess:"always",allowFullScreen:"true",menu:"false",flashvars:"",wmode:"window"};swfobject.embedSWF("<?php echo $sFile; ?>","flashContent","<?php echo $width; ?>","<?php echo $height; ?>","9.0",null,null,params,{name:"flashContent"});/*]]>*/</script>
<noscript>
<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" id="u35941" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0" bgcolor="#FFFFFF" width="<?php echo $width; ?>" height="<?php echo $height; ?>">
<param name="movie" value="<?php echo $sFile; ?>" />
<param name="LOOP" value="false" />
<param name="SCALE" value="exactfit" />
<param name="allowScriptAccess" value="always" />
<param name="allowFullScreen" value="true" />
<param name="menu" value="false" />
<param name="wmode" value="window" />
<param name="FlashVars" value="" />
<embed src="<?php echo $sFile; ?>" bgcolor="#FFFFFF" width="<?php echo $width; ?>" height="<?php echo $height; ?>" loop="false" pluginspage="http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash" type="application/x-shockwave-flash" scale="exactfit" allowscriptaccess="always" allowFullScreen="true" menu="false" flashvars=""></embed>
</object>
</noscript>
<br/>
</td>
</tr>
</table>
<br/>
<table width="<?php if($size=="tiny"){ echo "960"; } else { echo $width; }?>" border="0" cellspacing="0" cellpadding="0">
<tr><td align="center" style="height:100px;display:block" class="code"><p style="display:none" id="msg" class="info">NOTE: Please like our pages and have fun!</p></td></tr>
<tr>
<td align="center" class="bar-middle"><a href="/" target="_blank">Home</a> | <a href="/top20players.asp" target="_blank">Top 20 Players</a> | <a href="/ucp.asp" target="_blank">Manage Account</a> | <a href="/aw-character.php" target="_blank">Character Page</a> | <a href="http://www.owlg.org" target="_blank">Sponsor website</a> | Game Size: <a href="?size=tiny" target="_top">Tiny</a> | <a href="?size=normal">Normal</a> | <a href="?size=large">Large</a> | <a href="?size=huge">HUGE</a><br />
<div align="center"><strong>All rights reserved, WiSeXC Entertainment LLC.<br />
<div align="center"><strong>Copyright &copy; 2011-2014 WiSeXC Entertainment, LLC.<br />
</tr>
</table>
</div>
</body>
</html>
