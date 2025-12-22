<?php if(!defined('PHPOK_SET')){die('<h3>Error...</h3>');}?><!-- 搜索框 -->
<div class="global_sub">站内搜索</div>
<div class="border_no_top">
	<div align="center">
	<table>
	<form method="post" action="search.php?act=searchlink">
	<tr>
		<td><input type="text" name="keywords" style="width:120px;" value="<?php echo $keywords;?>"></td>
	</tr>
	<tr>
		<td>
			<select name="stype">
			<option value="">全部</option>
			<option value="article"<?php if($stype == "article"){?> selected<?php } ?>>文章</option>
			<option value="picture"<?php if($stype == "picture"){?> selected<?php } ?>>图片</option>
			<option value="download"<?php if($stype == "download"){?> selected<?php } ?>>下载</option>
			<option value="product"<?php if($stype == "product"){?> selected<?php } ?>>产品</option>
			</select>
			<input type="submit" value=" 搜 索 " class="button">
		</td>
	</tr>
	</form>
	</table>
	</div>
</div>