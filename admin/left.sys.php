<?php
/*if($_SESSION["admin"]["typer"] != "system")
{
	$rs = $DB->qgGetAll("SELECT * FROM ".$prefix."sysmenu WHERE adminer LIKE '%".$_SESSION["admin"]["typer"]."%' ORDER BY taxis ASC,id DESC");
}
else
{
	$rs = $DB->qgGetAll("SELECT * FROM ".$prefix."sysmenu ORDER BY taxis ASC,id DESC");
}
$left_menu = $DB->qgGetAll("SELECT * FROM ".$prefix."sysmenu ORDER BY rootid ASC,taxis ASC,id DESC");
*/

$left_menu = $DB->qgGetAll("SELECT id,groupname,sign FROM ".$prefix."sysgroup ORDER BY id ASC");
$TPL->p("left");
exit;
?>