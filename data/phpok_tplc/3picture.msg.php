<?php if(!defined('PHPOK_SET')){die('<h3>Error...</h3>');}?><!-- 图片内容页默认模板 -->
<?php QG_C_TEMPLATE::p("inc.head","","0");?>
<div align="center">
<div id="body">
<div class="table">

<div id="left_1">
	<div><?php QG_C_TEMPLATE::p("inc.login","","0");?></div>
	<div class="space_between"><div class="space_between"></div></div>

	<div class="global_sub">当前分类 &nbsp;<?php if($rs[parentid]){?><a href="list.php?id=<?php echo $rs[parentid];?>">上级分类</a><?php } ?></div>
	<div class="border_no_top">
		<?php $_i=0;$left_catelist=(is_array($left_catelist))?$left_catelist:array();foreach($left_catelist AS  $key=>$value){$_i++; ?>
		<div style="padding-left:20px;"><a href="list.php?id=<?php echo $value[id];?>" style="<?php echo $value[style];?><?php if($value[id] == $cateid){?>;color:red;<?php } ?>"><?php echo $value[catename];?></a></div>
		<?php } ?>
	</div>
	<div class="space_between"><div class="space_between"></div></div>
	<div class="global_sub">Google广告</div>
	<div class="border_no_top">
		<div align="center" style="padding-top:3px;padding-bottom:3px;"><?php echo QGMOD_AD(5);?></div>
	</div>
</div>

<div id="middle_1"><div class="space_between"></div></div>


<div id="right_1">
	<div class="global_sub">内容：<?php echo $rs[subject];?></div>
	<div class="border_no_top">
		<div class="msg_sub"><?php echo $rs[subject];?></div>
		<div class="msg_date">发布时间：<?php echo $rs[postdate];?> &nbsp; 点击率：<?php echo $rs[hits];?></div>
		<div class="msg_content"><?php echo $content;?><div style="clear:both;"></div></div>
		<div align="right"><?php echo $pagelist;?></div>
	</div>
</div>
<div style="clear:both;"></div>

</div>
</div>
</div>

<?php QG_C_TEMPLATE::p("inc.foot","","0");?>