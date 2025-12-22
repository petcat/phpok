<?php if(!defined('PHPOK_SET')){die('<h3>Error...</h3>');}?><!-- 公共页脚 -->
<div align="center">
<div id="foot">
<?php $debugmsg = QGMOD_FOOT();?>
	<div align="center">
		<div class="table">
			<div>Copyright &copy; 2004-2008 <a href="http://www.sinogacma.cn">Sino Gacma</a> <a href="admin.php">[ICP 09015958]</a></div>
			<div><?php echo $debugmsg;?> &nbsp;</div>
		</div>
	</div>
</div>
</div>
<script type="text/javascript">
function kill_errors()
{
	return true;
}
window.onerror = kill_errors;
</script>
</body>
</html>