<?php if(!defined('PHPOK_SET')){die('<h3>Error...</h3>');}?><?php QG_C_TEMPLATE::p("inc.head","","0");?>
<div align="center">
<div id="body">
<div class="table">


<div id="left_1">
	<div><?php QG_C_TEMPLATE::p("inc.login","","0");?></div>
	<div class="space_between"><div class="space_between"></div></div>
	<div class="global_sub">相关分类</div>
	<div class="border_no_top">
		<?php $_i=0;$left_catelist=(is_array($left_catelist))?$left_catelist:array();foreach($left_catelist AS  $key=>$value){$_i++; ?>
		<div style="padding-left:20px;"><a href="<?php echo $value[url];?>" style="<?php echo $value[style];?><?php if($value[id] == $id){?>;color:red;<?php } ?>"<?php echo $value[target];?>><?php echo $value[subject];?></a></div>
		<?php } ?>
	</div>
</div>

<div id="middle_1"><div class="space_between"></div></div>

<div id="right_1">
	<div class="global_sub"><?php echo $subject;?></div>
	<div class="border_no_top">
		<div style="padding:5px;">
			<div><?php echo $content;?></div>
			<?php if($pagelist && is_array($pagelist)){?>
			<div align="right">
			<div class="pagelist">
				<?php $_i=0;$pagelist=(is_array($pagelist))?$pagelist:array();foreach($pagelist AS  $key=>$value){$_i++; ?>
					<?php if($value[status]){?>
					<span class="m"><strong><?php echo $value[name];?></strong></span>
					<?php }else{ ?>
					<span class="n"><a href="<?php echo $value[url];?>"><?php echo $value[name];?></a></span>
					<?php } ?>
				<?php } ?>
			</div>
			<div>
			<?php } ?>
		</div>
	</div>
</div>
<div style="clear:both;"></div>

</div>
</div>
</div>
<?php QG_C_TEMPLATE::p("inc.foot","","0");?>