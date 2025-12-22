<?php if(!defined('PHPOK_SET')){die('<h3>Error...</h3>');}?><?php QG_C_TEMPLATE::p("global_head","","0");?>
<?php QG_C_TEMPLATE::p("right.css","","0");?>
<?php QG_C_TEMPLATE::p("right.head","","0");?>

<!-- 文件上传管理 -->
<div align="center">
<table width="100%">
<tr>
	<td class="qg_notice" style="text-align:left;">
	<a href="admin.php?file=index">系统首页</a> &raquo;
	<a href="admin.php?file=upfiles&act=list">附件列表</a> &raquo;
	<?php if($sysAct == "add_link"){?>
	经FTP上传的附件进行链接添加
	<?php }elseif($sysAct == "add_xupfiles"){ ?>
	使用控件上传附件
	<?php }elseif($sysAct == "upfiles"){ ?>
	普通附件上传 （仅提供支持zip、rar、tar.gz、jpg、gif和png六种格式的小文件上传）
	<?php }else{ ?>
	已上传附件 &nbsp; 外链文件或不存在文件不允许修改
	<?php } ?>
	</td>
</tr>
</table>
</div>

<?php if($sysAct == "add_link"){?>
<table width="100%">
<tr>
	<td class="qg_notice">添加前要求先上传文件，注意，上传的文件请使用英文、数字或下划线等命名，不要使用中文及空格</td>
</tr>
</table>
<div class="table" onmouseover="this.className='table table1'" onmouseout="this.className='table'">
<table width="100%">
<form method="post" action="admin.php?file=upfiles&act=add_linkok" onsubmit="return add_msg();">
<tr>
	<td class="left">附件名称：<span style="color:red;">*</span></td>
	<td class="right">
		<input type="text" name="tmpname" id="tmpname">
		<span class="clue_on">[请填写附件的名称，支持中文，不超过50个汉字]</span>
	</td>
</tr>
</table>
</div>

<div class="table" onmouseover="this.className='table table1'" onmouseout="this.className='table'">
<table width="100%">
<tr>
	<td class="left">附件地址：<span style="color:red;">*</span></td>
	<td class="right">
		<input type="text" name="fold_file" id="fold_file" class="long_input">
		<span class="clue_on">[相对于网站根目录下的文件路径]</span>
	</td>
</tr>
</table>
</div>

<div class="table" onmouseover="this.className='table table1'" onmouseout="this.className='table'">
<table width="100%">
<tr>
	<td class="left">&nbsp;</td>
	<td class="right">
		<span class="clue_on">附件地址仅限：英文、数字及下划线，使用其他字符可能会出现找不到文件现象</span>
	</td>
</tr>
</table>
</div>

<div class="table" onmouseover="this.className='table table1'" onmouseout="this.className='table'">
<table width="100%">
<tr>
	<td class="left">发布时间：</td>
	<td class="right">
		<input type="text" name="postdate" id="postdate" readonly value="<?php echo date('Y-m-d',$system_time);;?>">
		<span class="clue_on">[选择一个发布时间]</span>
	</td>
</tr>
</table>
</div>

<div class="table">
<table width="100%">
<tr>
	<td class="left">&nbsp;</td>
	<td class="right"><input type="submit" id="creat_button" class="mybutton_01" value="添加一个新链接"> &nbsp; <span id="show_step" style="color:red;"></span></td>
</tr>
</form>
</table>
</div>
<script type="text/javascript">
function add_msg()
{
	var tmpname = $("tmpname").value;
	var foldfile = $("fold_file").value;
	if(!foldfile || !tmpname)
	{
		alert("名称和文件都不允许为空");
		return false;
	}

	$("show_step").innerHTML = "<img src='admin/tpl/images/loading.gif' border='0' align='absmiddle'> 正在保存数据，如果网页没有动静请刷新...";

	$("creat_button").disabled = true;
}
</script>

<?php }elseif($sysAct == "add_xupfiles"){ ?>
<table width="100%">
<tr>
	<td class="qg_notice">如果您的客户端未安装XuploadFiles控件，<a href="upfiles/xuploadfiles.cab">请点击这里下载</a>，该控件仅支持IE6和IE7浏览器！</td>
</tr>
</table>
<object id="uploadid" height="0" width="0" classid="clsid:18B9E4BF-F21F-46B9-AD50-5CA62145426A" codebase="upfiles/xuploadfiles.cab">
<param name="Action" value="admin.php?file=xu_upfiles">
<param name="MinFileSize" value="1">
<param name="MaxFileSize" value="314572800">
<param name="MaxFileCount" value="10000">
<param name="MaxTotalSize" value="1048576000">
<param name="AllowExt" value="gif;jpg;png;zip;rar;gz">
<param name="DenyExt" value="asp;aspx;php">
</object>
<div class="table">
<table width="100%">
<tr>
	<td class="left">&nbsp;</td>
	<td class="right">
		<input type="button" value="选择上传文件" onClick="filelist.value=uploadid.selectfiles();">
		<input type="button" value="上 传" onClick="uploadid.uploadfile();filelist.value='';tourl();">
	</td>
</tr>
</table>
</div>
<table width="100%">
<tr>
	<td><textarea name="filelist" readonly style="width:100%;height:300px;"></textarea></td>
</tr>
</table>
<script type="text/javascript">
function tourl()
{
	alert("图片上传成功");
	return true;
}
</script>

<?php }elseif($sysAct == "list"){ ?>

<script type="text/javascript">
function qg_delete(id)
{
	if(!id || id == "0")
	{
		alert("ID号不允许为空！");
		return false;
	}
	question = confirm("确认删除该附件吗？特别说明，删除后无法恢复！");
	if (question != "0")
	{
		window.location.href="admin.php?file=upfiles&act=delete&id="+id;
	}
	return true;
}
function upfile_viewpic(id)
{
	var url = "admin.php?file=open.viewpic&id="+id;
	qg_open(url,"500");
}
</script>
<table width="100%">
<tr>
	<?php $_i=0;$piclist=(is_array($piclist))?$piclist:array();foreach($piclist AS  $key=>$value){$_i++; ?>
	<td align="center">
		<table>
		<tr>
			<td><img src="<?php echo $value[thumb];?>" width="120px" height="120px" border="0" onclick="upfile_viewpic(<?php echo $value[id];?>);" style="cursor:pointer;"></td>
		</tr>
		<form method="post" action="admin.php?file=upfiles&act=modifyok&id=<?php echo $value[id];?>">
		<tr>
			<td><input type="text" name="tmpname" value="<?php echo $value[tmpname];?>" style="width:120px"></td>
		</tr>
		<tr>
			<td><input type="submit" value="更改" /> <input type="button" value="删除" onclick="qg_delete(<?php echo $value[id];?>)" style="cursor:pointer;" /></td>
		</tr>
		</form>
		</table>
	</td>
	<?php if($_i%5===0){echo '</tr><tr>';}?>
	<?php } ?>
</tr>
</table>

<?php if($attlist && is_array($attlist)){?>
	<table width="100%">
	<tr>
		<td class="qg_notice" style="text-align:left;">可下载附件</td>
	</tr>
	</table>
	<?php $_i=0;$attlist=(is_array($attlist))?$attlist:array();foreach($attlist AS  $key=>$value){$_i++; ?>
		<div class="table" onmouseover="this.className='table table1'" onmouseout="this.className='table'">
		<table width="100%" cellpadding="0" cellspacing="0">
		<tr>
			<td class="qg_tdheight" width="10px" height="25px">&nbsp;</td>
			<form method="post" action="admin.php?file=upfiles&act=modifyok&id=<?php echo $value[id];?>">
				<td align="left"><input type="text" name="tmpname" value="<?php if($value[tmpname]){?><?php echo $value[tmpname];?><?php }else{ ?><?php echo $value[filename];?><?php } ?>" style="width:200px;"> <input type="submit" value="更改" /></td>
			</form>
			<td style="width:80px;text-align:right;" title="文件大小：<?php echo $value[filesize];?>"><?php echo $value[filesize];?></td>
			<td style="width:140px;text-align:center;" title="上传时间：<?php echo $value[postdate];?>"><?php echo $value[postdate];?></td>
			<td style="width:30px;text-align:center;"><a href="<?php echo $value[folder];?><?php echo $value[filename];?>" target="_blank" title="下载附件"><img src="admin/tpl/images/download.gif" border="0" title="下载附件"></a></td>
			<td style="width:20px;text-align:center;"><img src="admin/tpl/images/files_del.gif" border="0" onclick="qg_delete(<?php echo $value[id];?>)" title="删除该附件" style="cursor:pointer;"></td>
		</tr>
		</table>
		</div>
	<?php } ?>

<?php } ?>

<?php if($extlist && is_array($extlist)){?>
	<table width="100%">
	<tr>
		<td class="qg_notice" style="text-align:left;">其他附件（外部链接或不存的附件）</td>
	</tr>
	</table>
	<?php $_i=0;$extlist=(is_array($extlist))?$extlist:array();foreach($extlist AS  $key=>$value){$_i++; ?>
		<div class="table" onmouseover="this.className='table table1'" onmouseout="this.className='table'">
		<table width="100%" cellpadding="0" cellspacing="0">
		<tr>
			<td class="qg_tdheight" width="10px" height="25px">&nbsp;</td>
			<td align="left"><input type="checkbox" name="filelist[]" value="<?php echo $value[id];?>"> <input type="text" name="tmpname" value="<?php if($value[tmpname]){?><?php echo $value[tmpname];?><?php }else{ ?><?php echo $value[filename];?><?php } ?>" disabled></td>
			<td align="left"><?php echo $value[folder];?><?php echo $value[filename];?></td>
			<td style="width:140px;text-align:center;"><?php echo $value[postdate];?></td>
			<td style="width:20px;text-align:center;"><img src="admin/tpl/images/files_del.gif" border="0" onclick="qg_delete(<?php echo $value[id];?>)" title="删除该附件" style="cursor:pointer;"></td>
		</tr>
		</table>
		</div>
	<?php } ?>
<?php } ?>

<?php if($pagelist){?>
<div align="right" style="padding-right:2px;">
<?php echo $pagelist;?>
</div>
<?php } ?>

<?php }elseif($sysAct == "upfiles"){ ?>
<table width="100%">
<tr>
	<td class="qg_notice">单个文件大小建议不要超过100KB，总文件大小建议不要超过500KB，如果超过，建议您使用大文件上传</td>
</tr>
</table>
<form method="post" action="admin.php?file=upfiles&act=upfilesok" enctype="multipart/form-data">
<?php $_i=0;$up_array=(is_array($up_array))?$up_array:array();foreach($up_array AS  $key=>$value){$_i++; ?>
<div class="table" onmouseover="this.className='table table1'" onmouseout="this.className='table'">
<table width="100%">
<tr>
	<td class="left">&nbsp;</td>
	<td class="right">
		名称：<input type="text" name="setname_<?php echo $value;?>">
		上传：<input type="file" name="iframeUpload_<?php echo $value;?>" style="width:300px;">
	</td>
</tr>
</table>
</div>
<?php } ?>
<br />
<div class="table">
<table width="100%">
<tr>
	<td class="left">&nbsp;</td>
	<td class="right"><input type="submit" value="确认上传"></td>
</tr>
</table>
</div>
</form>

<?php } ?>

<?php QG_C_TEMPLATE::p("foot","","0");?>