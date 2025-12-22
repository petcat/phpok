<?php if(!defined('PHPOK_SET')){die('<h3>Error...</h3>');}?><!-- 站内公告页模板 -->
<?php QG_C_TEMPLATE::p("inc.head","","0");?>
<div align="center">
<div id="body">
<div class="table">

<div id="left_1">
	<div><?php QG_C_TEMPLATE::p("inc.login","","0");?></div>
	<div class="space_between"><div class="space_between"></div></div>
	<div class="global_sub">Google广告</div>
	<div class="border_no_top">
		<div align="center" style="padding-top:3px;padding-bottom:3px;">
<script type="text/javascript"><!--
google_ad_client = "pub-3996640094115586";
google_ad_width = 160;
google_ad_height = 600;
//-->
</script>
<script type="text/javascript"
src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
		</div>
	</div>
</div>

<div id="middle_1"><div class="space_between"></div></div>

<div id="right_1">
	<div class="global_sub">网站公告</div>
	<div class="border_no_top">
		<?php $_i=0;$list=(is_array($list))?$list:array();foreach($list AS  $key=>$value){$_i++; ?>
		<div class="dtr"><a name="<?php echo $value[id];?>"></a><a href="notice.php?id=<?php echo $value[id];?>#<?php echo $value[id];?>"><?php echo $value[subject];?></a></div>
		<div class="wtr">
			<div style="padding:5px;">
				<div><?php echo $value[content];?></div>
				<div align="right"><?php echo $value[postdate];?></div>
			</div>
		</div>
		<?php } ?>
	</div>
</div>
<div style="clear:both;"></div>

</div>
</div>
</div>

<?php QG_C_TEMPLATE::p("inc.foot","","0");?>