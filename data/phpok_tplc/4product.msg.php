<?php if(!defined('PHPOK_SET')){die('<h3>Error...</h3>');}?><!-- 产品内容页默认模板 -->
<?php QG_C_TEMPLATE::p("inc.head","","0");?>
<div align="center">
<div id="body">
<div class="table">

<div id="left_1">
	<div><?php QG_C_TEMPLATE::p("inc.login","","0");?></div>
	<div class="space_between"><div class="space_between"></div></div>

	<div class="global_sub">Category &nbsp;<?php if($rs[parentid]){?><a href="list.php?id=<?php echo $rs[parentid];?>">Parent</a><?php } ?></div>
	<div class="border_no_top">
		<?php $_i=0;$left_catelist=(is_array($left_catelist))?$left_catelist:array();foreach($left_catelist AS  $key=>$value){$_i++; ?>
		<div style="padding-left:20px;"><a href="list.php?id=<?php echo $value[id];?>" style="<?php echo $value[style];?><?php if($value[id] == $cateid){?>;color:red;<?php } ?>"><?php echo $value[catename];?></a></div>
		<?php } ?>
	</div>
	<div class="space_between"><div class="space_between"></div></div>
	<div class="global_sub">&nbsp;</div>
	<div class="border_no_top">
		<div align="center" style="padding-top:3px;padding-bottom:3px;"><?php echo QGMOD_AD(4);?></div>
	</div>
</div>

<div id="middle_1"><div class="space_between"></div></div>


<div id="right_1">
	<div class="global_sub">Product: <?php echo $rs[subject];?></div>
	<div class="border_no_top">
		<div style="padding:5px;">
			<div class="dtr">Subject: <?php echo $rs[subject];?> <span style="font:italic 10px Arial,Tahoma;color:#C3C3C3">(<?php echo $rs[postdate];?>)</span></div>
			<div class="wtr">Standard: <?php echo $rs[ext_standard];?></div>
			<div class="dtr">Number: <?php echo $rs[ext_number];?></div>
			<div class="wtr"><?php echo $content;?><div style="clear:both;"></div></div>
		</div>
		<div align="right"><b><font color="#FF0000">☆</font></b><?php echo $pagelist;?></div>
		<!--<div class="dtr">Market Price: <?php echo $rs[ext_marketprice];?> 元</div>-->
		<!--<div class="wtr">Shop Price: $<span style="color:red;"><?php echo $rs[ext_shopprice];?></span></div>-->
		<div align="center" style="padding:5px;">
			<a href="buy.php?id=<?php echo $rs[id];?>"><img src="templates/en/crystal_blue/images/buy.gif" border="0"></a>
		</div>
	</div>
</div>
<div style="clear:both;"></div>

</div>
</div>
</div>

<?php QG_C_TEMPLATE::p("inc.foot","","0");?>