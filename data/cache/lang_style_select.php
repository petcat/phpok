<?php die('forbidden'); ?>
<table cellpadding='1' cellspacing='0'><tr><td><select onchange='sys_change_lang(this.value)'></select></td><td><select onchange='sys_change_tpl(this.value)'></select></td></tr></table><span style='display:none;'><script type='text/javascript'>
function sys_change_lang(m){var url='home.php?langsign='+m;window.location.href=url;}
function sys_change_tpl(m){var url='home.php?template='+m;window.location.href=url;}</script></span>