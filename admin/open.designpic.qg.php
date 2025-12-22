<?php
#[预览大图]
$system_url = GetSystemUrl();
$url = SafeHtml($url);
?>
<script type="text/javascript">
function ok()
{
	return true;
}
</script>
<img src="<?php echo $system_url;?>/<?php echo $url;?>" border="0">