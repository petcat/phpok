<?php if(!defined('PHPOK_SET')){die('<h3>Error...</h3>');}?><script type="text/javascript" src="admin/tpl/images/open.window.js"></script>
<style type="text/css">
.qg_notice
{
	border:1px #2693FF solid;
	background:#CAE4FF;
	padding:5px;
	text-align:center;
	color:red;
}
</style>
<div class="Top">
<table width="800px" cellpadding="0" cellspacing="1">
<tr>
	<td width="25px" align="right"><img src="admin/tpl/images/adminer.gif" border="0" align="absmiddle"></td>
	<td>&nbsp;管理员：<?php echo $_SESSION[admin][user];?> [<?php echo $ADMIN_TYPER;?>]</td>
	<td width="120px"><select align="absmiddle" onchange="window.location=('admin.php?file=index&langid='+this.options[this.selectedIndex].value+'')">
		<?php $_i=0;$lang_array=(is_array($lang_array))?$lang_array:array();foreach($lang_array AS  $key=>$value){$_i++; ?>
			<option value="<?php echo $value[id];?>"<?php echo $value[select];?>><?php echo $value[name];?></option>
		<?php } ?>
	</select></td>
	<?php unset($lang_array);?>
	<td align="center" width="80px"><img src="admin/tpl/images/lead.gif" align="absmiddle" border="0"> <a href="admin.php?file=cache&act=list">缓存管理</a></td>
	<td align="center" width="80px"><img src="admin/tpl/images/lead.gif" align="absmiddle" border="0"> <a href="admin.php?file=index">系统首页</a></td>
	<td align="center" width="80px"><img src="admin/tpl/images/lead.gif" align="absmiddle" border="0"> <a href="index.php" target="_top">网站首页</a></td>
	<td align="right" width="80px"><img src="admin/tpl/images/logout.gif" border="0" align="absmiddle"> <a href="admin.php?act=logout" target="_top" title="退出">退出</a></td>
</tr>
</table>
</div>
<?php if($right_head_language){?>
<div align="center">
<table width="100%">
<tr>
	<td class="qg_notice">您好，站点没有正在使用的语言环境，请检查...</td>
</tr>
</table>
</div>
<?php unset($right_head_language);?>
<?php } ?>
<?php if($right_head_notice){?>
<div align="center">
<table width="100%">
<tr>
	<td class="qg_notice">您好，您当前的语言环境未设置相关的站点配置信息，这可能会造成信息设置的错误！请先进行网站常规信息设置</td>
</tr>
</table>
</div>
<?php unset($right_head_notice);?>
<?php } ?>