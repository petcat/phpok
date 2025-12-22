<?php if(!defined('PHPOK_SET')){die('<h3>Error...</h3>');}?><?php $playerlist = QGMOD_PLAYER();?>
<?php if($playerlist && count($playerlist)>0 && is_array($playerlist)){?>
<SCRIPT type="text/javascript">
if(qgIE == "FF")
{
	filter_code = "filter:progid:dximagetransform.microsoft.wipe(gradientsize=1.0,wipestyle=4, motion=forward);";
}
else
{
	filter_code = "filter:progid:DXImageTransform.Microsoft.RevealTrans(duration=5, transition=23);"
}

var widths=730;
var heights=200;
var counts=<?php echo count($playerlist);?>;//获取翻页图片数量
<?php $_i=0;$playerlist=(is_array($playerlist))?$playerlist:array();foreach($playerlist AS  $key=>$value){$_i++; ?>
img<?php echo $key;?>=new Image();
img<?php echo $key;?>.src="<?php echo $value[img];?>";
url<?php echo $key;?>=new Image ();
url<?php echo $key;?>.src="<?php echo $value[url];?>";
<?php } ?>
var nn=1;
var key=0;
function change_img()
{
	if(key==0)
	{
		key=1;
	}
	else if(document.all)
	{
		$("qgplayer_pic").filters[0].Apply();
		$("qgplayer_pic").filters[0].Play(duration=2);
	}
	eval('$("qgplayer_pic").src=img'+nn+'.src');
	if(qgIE != "FF")
	{
		eval('$("qgplayer_pic").filters.item(0).transition=23');
	}
	else
	{
		for(p_i=0;p_i<11;p_i++)
		{
			var p_i_i = 0.1 * p_i;
			var p_i_m = 100 * p_i;
			window.setTimeout("eval(ff_filter("+p_i_i+"))",p_i_m);
		}
	}
	eval('$("qgplayer_url").href=url'+nn+'.src');
	for (var i=1;i<=counts;i++)
	{
		$("xxjdjj"+i).className='axx';
	}
	$("xxjdjj"+nn).className='bxx';
	nn++;
	if(nn>counts)
	{
		nn=1;
	}
	tt=setTimeout('change_img()',4000);
}
function changeimg(n)
{
	nn=n;
	window.clearInterval(tt);
	change_img();
}

function ff_filter(t)
{
	var m = '$("qgplayer_pic").style.cssText = "-moz-opacity:'+t+';"';
	return m;
}

document.write('<style>');
document.write('.axx{padding:1px 7px;margin:1px;}');
document.write('a.axx:link,a.axx:visited{text-decoration:none;color:#fff;line-height:12px;font:11px sans-serif;background-color:#666;}');
document.write('a.axx:active,a.axx:hover{text-decoration:none;color:#fff;line-height:12px;font:11px sans-serif;background-color:#999;}');
document.write('.bxx{padding:1px 7px;margin:1px;}');
document.write('a.bxx:link,a.bxx:visited{text-decoration:none;color:#fff;line-height:12px;font:11px sans-serif;background-color:#D34600;}');
document.write('a.bxx:active,a.bxx:hover{text-decoration:none;color:#fff;line-height:12px;font:11px sans-serif;background-color:#D34600;}');
document.write('</style>');
document.write('<div style="width:'+widths+'px;height:'+heights+'px;overflow:hidden;text-overflow:clip;">');
var xljw_filter = filter_code;
document.write('<div><a id="qgplayer_url" target="_blank"><img id="qgplayer_pic" style="border:0px;'+xljw_filter+'" width='+widths+' height='+heights+' /></a></div>');
document.write('<div style="text-align:right;top:-20px;right:5px;position:relative;height:15px;padding:0px;margin:1px;border:0px;">');
for(var i=1;i<counts+1;i++)
{
	document.write('<a href="javascript:changeimg('+i+');" id="xxjdjj'+i+'" class="axx" target="_self">'+i+'</a>');
}
document.write('</div></div>');
change_img();
</SCRIPT>
<?php unset($playerlist);?>
<?php } ?>