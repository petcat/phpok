<?php if(!defined('PHPOK_SET')){die('<h3>Error...</h3>');}?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="admin/tpl/images/popen.css" />
<style type="text/css">
.piclist_default
{
	text-align:center;
	width:20%;
	height:94px;
	border:1px #D4D4D4 solid;
}
.piclist_show
{
	text-align:center;
	width:20%;
	height:94px;
	border:1px #FF0000 dashed;
}
</style>
<script type="text/javascript" src="admin/tpl/images/global.js"></script>
<script type="text/javascript">
if(qgIE == "IE" || qgIE == "IE6")
{
	var mEditor = window.parent.dialogArguments.$("<?php echo $id;?>");
}
else
{
	var mEditor = window.parent.opener.$("<?php echo $id;?>");
}
function ok()
{
	return true;
}
//操作选中的图片,type可以是add或del
function qg_setpic(imgurl,mid)
{
	var count_pic = "<?php echo count($msglist);?>";
	for(i=0;i<count_pic;i++)
	{
		var idname = "qg_img_"+i;
		if(idname == mid)
		{
			$(idname).className = "piclist_show";
		}
		else
		{
			$(idname).className = "piclist_default";
		}
	}
	mEditor.value = imgurl;
}
</script>
</head>
<body>
<table width="100%" cellpadding="0" cellspacing="1">
<tr>
	<form method="post" action="admin.php?file=open.index.img&act=addok&id=<?php echo $id;?>" enctype="multipart/form-data">
	<td style="border-bottom:1px #D4D4D4 solid;">
		<input type="file" name="iframeUpload">
		<input type="submit" value="上传">
	</td>
	</form>
</tr>
</table>
<table width="100%">
<tr>
	<?php $_i=0;$msglist=(is_array($msglist))?$msglist:array();foreach($msglist AS  $key=>$value){$_i++; ?>
	<td class="piclist_default" align="center" id="qg_img_<?php echo $key;?>"><img src="<?php echo $value[show_pic];?>" width="90px" height="90px" border="0" onclick="qg_setpic('<?php echo $value[folder];?><?php echo $value[filename];?>','qg_img_<?php echo $key;?>');" style="cursor:pointer;"></td>
	<?php if($_i%5===0){echo '</tr><tr>';}?>
	<?php } ?>
</tr>
</table>
<?php if($pagelist){?>
<div align="right" style="padding-right:2px;">
<?php echo $pagelist;?>
</div>
<?php } ?>
</body>
</html>