<?php if(!defined('PHPOK_SET')){die('<h3>Error...</h3>');}?><?php QG_C_TEMPLATE::p("global_head","","0");?>
<?php QG_C_TEMPLATE::p("right.css","","0");?>
<?php QG_C_TEMPLATE::p("right.head","","0");?>

<!-- 缓存文件管理 -->
<div align="center">
<table width="100%">
<tr>
	<td class="qg_notice" style="text-align:left;">
		<a href="admin.php?file=index">系统首页</a>
		&raquo; <a href="admin.php?file=cache&act=list">清空缓存操作</a>
		&raquo; 列表</td>
</tr>
</table>
</div>

<div class="table">
<input type="button" value="清空网站编译缓存" class="mybutton_01" onclick="qg_delfolder('phpok_tplc')">
&nbsp;
<input type="button" value="清空后台编译缓存" class="mybutton_01" onclick="qg_delfolder('admin_tplc')">
&nbsp;
<input type="button" value="清空文件缓存" class="mybutton_01" onclick="qg_delfolder('cache')">


</div>

<script type="text/javascript">
function qg_delfolder(name)
{
	qgurl("admin.php?file=cache&act=delete&delfolder="+name);
}
</script>
<?php QG_C_TEMPLATE::p("foot","","0");?>