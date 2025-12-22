<?php
#[============================]
#	Filename: search.php
#	Note	: 搜索
#	Version : 2.0
#	Author  : qinggan
#	Update  : 2008-02-15
#[============================]
require_once("global.php");
$sysact = $_GET["act"] ? $_GET["act"] : $_POST["act"];
if($sysact == "searchok")
{
	$keywords = SafeHtml(rawurldecode($_GET["keywords"]));
	if(!$keywords)
	{
		qgheader();
	}
	#[整理]
	$stype = SafeHtml($_GET["stype"]);
	$id_array = array();
	if($stype)
	{
		$stmt = $DB->prepare("SELECT c.id FROM ".$prefix."category AS c,".$prefix."sysgroup AS s WHERE s.sign=? AND c.sysgroupid=s.id AND c.status='1' AND c.language=?");
		$stmt->bindValue(1, $stype, SQLITE3_TEXT);
		$stmt->bindValue(2, LANGUAGE_ID, SQLITE3_TEXT);
		$result = $stmt->execute();
		$idlist = array();
		while($row = $DB->fetchArray($result)) {
			$idlist[] = $row;
		}
	}
	else
	{
		$stmt = $DB->prepare("SELECT id FROM ".$prefix."category WHERE status='1' AND language=?");
		$stmt->bindValue(1, LANGUAGE_ID, SQLITE3_TEXT);
		$result = $stmt->execute();
		$idlist = array();
		while($row = $DB->fetchArray($result)) {
			$idlist[] = $row;
		}
	}
	unset($sql);
	if(!$idlist)
	{
		qgheader();
	}
	foreach($idlist AS $key=>$value)
	{
		$id_array[] = $value["id"];
	}
	$idin = implode(",",$id_array);
	unset($id_array,$idlist);
	define("QGLIST_ID",0);#[定义常量QGLIST_ID]
	define("QGLIST_IDIN",$idin);#[定义常量QGLIST_IDIN，以供模块调用]
	#[]

	# 防止SQL注入，需要对关键词进行处理
	$escaped_keywords = '%'.$keywords.'%';
	$condition = " FROM ".$prefix."msg AS m,".$prefix."category AS c WHERE m.cateid in(".$idin.") AND m.ifcheck='1' AND m.subject LIKE ? AND m.cateid=c.id";
	$count = intval($_GET["count"]);
	if(!$count || $count<1)
	{
		$stmt = $DB->prepare("SELECT count(*) AS countid ".$condition);
		$stmt->bindValue(1, $escaped_keywords, SQLITE3_TEXT);
		$result = $stmt->execute();
		$s_count = $DB->fetchArray($result);
		$count = $s_count["countid"];
		unset($s_count);
	}
	$pageid = intval($_GET["pageid"]);
	$psize = 30;#[每页保留30条搜索]
	$offset = $pageid>0 ? ($pageid-1)*$psize : 0;
	# 修复分页查询，使用参数化查询
	$sql = "SELECT m.id,m.cateid,m.subject,m.postdate,m.hits,m.ext_docket,c.catename ".$condition." ORDER BY istop DESC,isvouch DESC,isbest DESC,orderdate DESC,postdate DESC,id DESC LIMIT ".$offset.",".$psize;
	$stmt = $DB->prepare($sql);
	$stmt->bindValue(1, $escaped_keywords, SQLITE3_TEXT);
	$result = $stmt->execute();
	$searchlist = array();
	while($row = $DB->fetchArray($result)) {
		$searchlist[] = $row;
	}
	if(!$searchlist)
	{
		qgheader();
	}
	$pageurl = "search.php?act=searchok&keywords=".rawurlencode($keywords)."&stype=".$stype."&count=".$count;
	$pagelist = page($pageurl,$count,$psize,$pageid);#[获取分页的数组]
	#[标题头]
	$keywords_safe = htmlspecialchars($keywords, ENT_QUOTES, 'UTF-8');
	$sitetitle = $langs["searchok"].":".$keywords_safe." - ".$system["sitename"];
	#[向导栏]
	$lead_menu[0]["url"] = pageurl;
	$lead_menu[0]["name"] = $langs["searchok"].":".$keywords_safe;
	HEAD();
	FOOT("search");
}
elseif($sysact == "searchlink")
{
	$keywords = SafeHtml($_POST["keywords"]);
	if(!$keywords)
	{
		qgheader();
	}
	$stype = SafeHtml($_POST["stype"]);
	$refreshurl = "search.php?act=searchok&keywords=".rawurlencode($keywords)."&stype=".$stype;
	qgheader($refreshurl);
}
else
{
	qgheader();
}
?>