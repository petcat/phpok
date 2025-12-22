<?php if(!defined('PHPOK_SET')){die('<h3>Error...</h3>');}?><!-- 搜索框 -->
<div class="global_sub">SEARCH</div>
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
			<option value="">ALL</option>
			<option value="article"<?php if($stype == "article"){?> selected<?php } ?>>Article</option>
			<option value="picture"<?php if($stype == "picture"){?> selected<?php } ?>>Picture</option>
			<option value="download"<?php if($stype == "download"){?> selected<?php } ?>>Download</option>
			<option value="product"<?php if($stype == "product"){?> selected<?php } ?>>Product</option>
			</select>
			<input type="submit" value=" Search " class="button">
		</td>
	</tr>
	</form>
	</table>
	</div>
</div>