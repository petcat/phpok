<?php
/**
 * xajax.inc.php :: Main xajax class and setup file
 *
 * xajax version 0.2.4
 * copyright (c) 2005 by Jared White & J. Max Wilson
 * http://www.xajaxproject.org
 */
if (!defined ('XAJAX_DEFAULT_CHAR_ENCODING'))
{
	define ('XAJAX_DEFAULT_CHAR_ENCODING', 'utf-8' );
}

####require_once(dirname(__FILE__)."/xajax_response.inc.php");


class xajaxResponse
{
	var $xml;
	var $sEncoding;
	var $bOutputEntities;

	function xajaxResponse($sEncoding=XAJAX_DEFAULT_CHAR_ENCODING, $bOutputEntities=false)
	{
		$this->setCharEncoding($sEncoding);
		$this->bOutputEntities = $bOutputEntities;
	}

	function setCharEncoding($sEncoding)
	{
		$this->sEncoding = $sEncoding;
	}

	function outputEntitiesOn()
	{
		$this->bOutputEntities = true;
	}

	function outputEntitiesOff()
	{
		$this->bOutputEntities = false;
	}

	function addConfirmCommands($iCmdNumber, $sMessage)
	{
		$this->xml .= $this->_cmdXML(array("n"=>"cc","t"=>$iCmdNumber),$sMessage);
	}

	function addAssign($sTarget,$sAttribute,$sData)
	{
		$this->xml .= $this->_cmdXML(array("n"=>"as","t"=>$sTarget,"p"=>$sAttribute),$sData);
	}

	function addAppend($sTarget,$sAttribute,$sData)
	{
		$this->xml .= $this->_cmdXML(array("n"=>"ap","t"=>$sTarget,"p"=>$sAttribute),$sData);
	}

	function addPrepend($sTarget,$sAttribute,$sData)
	{
		$this->xml .= $this->_cmdXML(array("n"=>"pp","t"=>$sTarget,"p"=>$sAttribute),$sData);
	}

	function addReplace($sTarget,$sAttribute,$sSearch,$sData)
	{
		$sDta = "<s><![CDATA[$sSearch]]></s><r><![CDATA[$sData]]></r>";
		$this->xml .= $this->_cmdXML(array("n"=>"rp","t"=>$sTarget,"p"=>$sAttribute),$sDta);
	}

	function addClear($sTarget,$sAttribute)
	{
		$this->addAssign($sTarget,$sAttribute,'');
	}

	function addAlert($sMsg)
	{
		$this->xml .= $this->_cmdXML(array("n"=>"al"),$sMsg);
	}

	function addRedirect($sURL)
	{
		$queryStart = strpos($sURL, '?', strrpos($sURL, '/'));
		if ($queryStart !== FALSE)
		{
			$queryStart++;
			$queryEnd = strpos($sURL, '#', $queryStart);
			if ($queryEnd === FALSE)
				$queryEnd = strlen($sURL);
			$queryPart = substr($sURL, $queryStart, $queryEnd-$queryStart);
			parse_str($queryPart, $queryParts);
			$newQueryPart = "";
			foreach($queryParts as $key => $value)
			{
				$newQueryPart .= rawurlencode($key).'='.rawurlencode($value).ini_get('arg_separator.output');
			}
			$sURL = str_replace($queryPart, $newQueryPart, $sURL);
		}
		$this->addScript('window.location = "'.$sURL.'";');
	}

	function addScript($sJS)
	{
		$this->xml .= $this->_cmdXML(array("n"=>"js"),$sJS);
	}

	function addScriptCall() {
		$arguments = func_get_args();
		$sFunc = array_shift($arguments);
		$sData = $this->_buildObjXml($arguments);
		$this->xml .= $this->_cmdXML(array("n"=>"jc","t"=>$sFunc),$sData);
	}

	function addRemove($sTarget)
	{
		$this->xml .= $this->_cmdXML(array("n"=>"rm","t"=>$sTarget),'');
	}

	function addCreate($sParent, $sTag, $sId, $sType="")
	{
		if ($sType)
		{
			trigger_error("The \$sType parameter of addCreate has been deprecated.  Use the addCreateInput() method instead.", E_USER_WARNING);
			return;
		}
		$this->xml .= $this->_cmdXML(array("n"=>"ce","t"=>$sParent,"p"=>$sId),$sTag);
	}

	function addInsert($sBefore, $sTag, $sId)
	{
		$this->xml .= $this->_cmdXML(array("n"=>"ie","t"=>$sBefore,"p"=>$sId),$sTag);
	}

	function addInsertAfter($sAfter, $sTag, $sId)
	{
		$this->xml .= $this->_cmdXML(array("n"=>"ia","t"=>$sAfter,"p"=>$sId),$sTag);
	}

	function addCreateInput($sParent, $sType, $sName, $sId)
	{
		$this->xml .= $this->_cmdXML(array("n"=>"ci","t"=>$sParent,"p"=>$sId,"c"=>$sType),$sName);
	}

	function addInsertInput($sBefore, $sType, $sName, $sId)
	{
		$this->xml .= $this->_cmdXML(array("n"=>"ii","t"=>$sBefore,"p"=>$sId,"c"=>$sType),$sName);
	}

	function addInsertInputAfter($sAfter, $sType, $sName, $sId)
	{
		$this->xml .= $this->_cmdXML(array("n"=>"iia","t"=>$sAfter,"p"=>$sId,"c"=>$sType),$sName);
	}

	function addEvent($sTarget,$sEvent,$sScript)
	{
		$this->xml .= $this->_cmdXML(array("n"=>"ev","t"=>$sTarget,"p"=>$sEvent),$sScript);
	}

	function addHandler($sTarget,$sEvent,$sHandler)
	{
		$this->xml .= $this->_cmdXML(array("n"=>"ah","t"=>$sTarget,"p"=>$sEvent),$sHandler);
	}

	function addRemoveHandler($sTarget,$sEvent,$sHandler)
	{
		$this->xml .= $this->_cmdXML(array("n"=>"rh","t"=>$sTarget,"p"=>$sEvent),$sHandler);
	}

	function addIncludeScript($sFileName)
	{
		$this->xml .= $this->_cmdXML(array("n"=>"in"),$sFileName);
	}

	function getXML()
	{
		$sXML = "<?xml version=\"1.0\"";
		if ($this->sEncoding && strlen(trim($this->sEncoding)) > 0)
			$sXML .= " encoding=\"".$this->sEncoding."\"";
		$sXML .= " ?"."><xjx>" . $this->xml . "</xjx>";

		return $sXML;
	}

	function loadXML($mXML)
	{
		if (is_a($mXML, "xajaxResponse")) {
			$mXML = $mXML->getXML();
		}
		$sNewXML = "";
		$iStartPos = strpos($mXML, "<xjx>") + 5;
		$sNewXML = substr($mXML, $iStartPos);
		$iEndPos = strpos($sNewXML, "</xjx>");
		$sNewXML = substr($sNewXML, 0, $iEndPos);
		$this->xml .= $sNewXML;
	}

	function _cmdXML($aAttributes, $sData)
	{
		if ($this->bOutputEntities) {
			if (function_exists('mb_convert_encoding')) {
				$sData = call_user_func_array('mb_convert_encoding', array(&$sData, 'HTML-ENTITIES', $this->sEncoding));
			}
			else {
				trigger_error("The xajax XML response output could not be converted to HTML entities because the mb_convert_encoding function is not available", E_USER_NOTICE);
			}
		}
		$xml = "<cmd";
		foreach($aAttributes as $sAttribute => $sValue)
			$xml .= " $sAttribute=\"$sValue\"";
		if ($sData !== null && !stristr($sData,'<![CDATA['))
			$xml .= "><![CDATA[$sData]]></cmd>";
		else if ($sData !== null)
			$xml .= ">$sData</cmd>";
		else
			$xml .= "></cmd>";

		return $xml;
	}

	function _buildObjXml($var) {
		if (gettype($var) == "object") $var = get_object_vars($var);
		if (!is_array($var)) {
			return "<![CDATA[$var]]>";
		}
		else {
			$data = "<xjxobj>";
			foreach ($var as $key => $value) {
				$data .= "<e>";
				$data .= "<k>" . htmlspecialchars($key) . "</k>";
				$data .= "<v>" . $this->_buildObjXml($value) . "</v>";
				$data .= "</e>";
			}
			$data .= "</xjxobj>";
			return $data;
		}
	}

}




if (!defined ('XAJAX_GET'))
{
	define ('XAJAX_GET', 0);
}
if (!defined ('XAJAX_POST'))
{
	define ('XAJAX_POST', 1);
}

class xajax
{
	var $aFunctions;
	var $aObjects;
	var $aFunctionRequestTypes;
	var $aFunctionIncludeFiles;
	var $sCatchAllFunction;
	var $sPreFunction;
	var $sRequestURI;
	var $sWrapperPrefix;
	var $bDebug;
	var $bStatusMessages;
	var $bExitAllowed;
	var $bWaitCursor;
	var $bErrorHandler;
	var $sLogFile;
	var $bCleanBuffer;
	var $sEncoding;
	var $bDecodeUTF8Input;
	var $bOutputEntities;
	var $aObjArray;
	var $iPos;

	function xajax($sRequestURI="",$sWrapperPrefix="xajax_",$sEncoding=XAJAX_DEFAULT_CHAR_ENCODING,$bDebug=false)
	{
		$this->aFunctions = array();
		$this->aObjects = array();
		$this->aFunctionIncludeFiles = array();
		$this->sRequestURI = $sRequestURI;
		if ($this->sRequestURI == "")
			$this->sRequestURI = $this->_detectURI();
		$this->sWrapperPrefix = $sWrapperPrefix;
		$this->bDebug = $bDebug;
		$this->bStatusMessages = false;
		$this->bWaitCursor = true;
		$this->bExitAllowed = true;
		$this->bErrorHandler = false;
		$this->sLogFile = "";
		$this->bCleanBuffer = false;
		$this->setCharEncoding($sEncoding);
		$this->bDecodeUTF8Input = false;
		$this->bOutputEntities = false;
	}

	function setRequestURI($sRequestURI)
	{
		$this->sRequestURI = $sRequestURI;
	}

	function setWrapperPrefix($sPrefix)
	{
		$this->sWrapperPrefix = $sPrefix;
	}

	function debugOn()
	{
		$this->bDebug = true;
	}

	function debugOff()
	{
		$this->bDebug = false;
	}

	function statusMessagesOn()
	{
		$this->bStatusMessages = true;
	}

	function statusMessagesOff()
	{
		$this->bStatusMessages = false;
	}

	function waitCursorOn()
	{
		$this->bWaitCursor = true;
	}

	function waitCursorOff()
	{
		$this->bWaitCursor = false;
	}

	function exitAllowedOn()
	{
		$this->bExitAllowed = true;
	}

	function exitAllowedOff()
	{
		$this->bExitAllowed = false;
	}

	function errorHandlerOn()
	{
		$this->bErrorHandler = true;
	}

	function errorHandlerOff()
	{
		$this->bErrorHandler = false;
	}

	function setLogFile($sFilename)
	{
		$this->sLogFile = $sFilename;
	}

	function cleanBufferOn()
	{
		$this->bCleanBuffer = true;
	}

	function cleanBufferOff()
	{
		$this->bCleanBuffer = false;
	}

	function setCharEncoding($sEncoding)
	{
		$this->sEncoding = $sEncoding;
	}

	function decodeUTF8InputOn()
	{
		$this->bDecodeUTF8Input = true;
	}

	function decodeUTF8InputOff()
	{
		$this->bDecodeUTF8Input = false;
	}

	function outputEntitiesOn()
	{
		$this->bOutputEntities = true;
	}

	function outputEntitiesOff()
	{
		$this->bOutputEntities = false;
	}

	function registerFunction($mFunction,$sRequestType=XAJAX_POST)
	{
		if (is_array($mFunction)) {
			$this->aFunctions[$mFunction[0]] = 1;
			$this->aFunctionRequestTypes[$mFunction[0]] = $sRequestType;
			$this->aObjects[$mFunction[0]] = array_slice($mFunction, 1);
		}
		else {
			$this->aFunctions[$mFunction] = 1;
			$this->aFunctionRequestTypes[$mFunction] = $sRequestType;
		}
	}

	function registerExternalFunction($mFunction,$sIncludeFile,$sRequestType=XAJAX_POST)
	{
		$this->registerFunction($mFunction, $sRequestType);

		if (is_array($mFunction)) {
			$this->aFunctionIncludeFiles[$mFunction[0]] = $sIncludeFile;
		}
		else {
			$this->aFunctionIncludeFiles[$mFunction] = $sIncludeFile;
		}
	}

	function registerCatchAllFunction($mFunction)
	{
		if (is_array($mFunction)) {
			$this->sCatchAllFunction = $mFunction[0];
			$this->aObjects[$mFunction[0]] = array_slice($mFunction, 1);
		}
		else {
			$this->sCatchAllFunction = $mFunction;
		}
	}

	function registerPreFunction($mFunction)
	{
		if (is_array($mFunction)) {
			$this->sPreFunction = $mFunction[0];
			$this->aObjects[$mFunction[0]] = array_slice($mFunction, 1);
		}
		else {
			$this->sPreFunction = $mFunction;
		}
	}

	function canProcessRequests()
	{
		if ($this->getRequestMode() != -1) return true;
		return false;
	}

	function getRequestMode()
	{
		if (!empty($_GET["xajax"]))
			return XAJAX_GET;

		if (!empty($_POST["xajax"]))
			return XAJAX_POST;

		return -1;
	}

	function processRequests()
	{

		$requestMode = -1;
		$sFunctionName = "";
		$bFoundFunction = true;
		$bFunctionIsCatchAll = false;
		$sFunctionNameForSpecial = "";
		$aArgs = array();
		$sPreResponse = "";
		$bEndRequest = false;
		$sResponse = "";

		$requestMode = $this->getRequestMode();
		if ($requestMode == -1) return;

		if ($requestMode == XAJAX_POST)
		{
			$sFunctionName = $_POST["xajax"];

			if (!empty($_POST["xajaxargs"]))
				$aArgs = $_POST["xajaxargs"];
		}
		else
		{
			header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
			header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
			header ("Cache-Control: no-cache, must-revalidate");
			header ("Pragma: no-cache");

			$sFunctionName = $_GET["xajax"];

			if (!empty($_GET["xajaxargs"]))
				$aArgs = $_GET["xajaxargs"];
		}

		if ($this->bErrorHandler) {
			$GLOBALS['xajaxErrorHandlerText'] = "";
			set_error_handler("xajaxErrorHandler");
		}

		if ($this->sPreFunction) {
			if (!$this->_isFunctionCallable($this->sPreFunction)) {
				$bFoundFunction = false;
				$objResponse = new xajaxResponse();
				$objResponse->addAlert("Unknown Pre-Function ". $this->sPreFunction);
				$sResponse = $objResponse->getXML();
			}
		}
		if (array_key_exists($sFunctionName,$this->aFunctionIncludeFiles))
		{
			ob_start();
			include_once($this->aFunctionIncludeFiles[$sFunctionName]);
			ob_end_clean();
		}

		if ($bFoundFunction) {
			$sFunctionNameForSpecial = $sFunctionName;
			if (!array_key_exists($sFunctionName, $this->aFunctions))
			{
				if ($this->sCatchAllFunction) {
					$sFunctionName = $this->sCatchAllFunction;
					$bFunctionIsCatchAll = true;
				}
				else {
					$bFoundFunction = false;
					$objResponse = new xajaxResponse();
					$objResponse->addAlert("Unknown Function $sFunctionName.");
					$sResponse = $objResponse->getXML();
				}
			}
			else if ($this->aFunctionRequestTypes[$sFunctionName] != $requestMode)
			{
				$bFoundFunction = false;
				$objResponse = new xajaxResponse();
				$objResponse->addAlert("Incorrect Request Type.");
				$sResponse = $objResponse->getXML();
			}
		}

		if ($bFoundFunction)
		{
			for ($i = 0; $i < sizeof($aArgs); $i++)
			{
				if (get_magic_quotes_gpc() == 1 && is_string($aArgs[$i])) {

					$aArgs[$i] = stripslashes($aArgs[$i]);
				}
				if (stristr($aArgs[$i],"<xjxobj>") != false)
				{
					$aArgs[$i] = $this->_xmlToArray("xjxobj",$aArgs[$i]);
				}
				else if (stristr($aArgs[$i],"<xjxquery>") != false)
				{
					$aArgs[$i] = $this->_xmlToArray("xjxquery",$aArgs[$i]);
				}
				else if ($this->bDecodeUTF8Input)
				{
					$aArgs[$i] = $this->_decodeUTF8Data($aArgs[$i]);
				}
			}

			if ($this->sPreFunction) {
				$mPreResponse = $this->_callFunction($this->sPreFunction, array($sFunctionNameForSpecial, $aArgs));
				if (is_array($mPreResponse) && $mPreResponse[0] === false) {
					$bEndRequest = true;
					$sPreResponse = $mPreResponse[1];
				}
				else {
					$sPreResponse = $mPreResponse;
				}
				if (is_a($sPreResponse, "xajaxResponse")) {
					$sPreResponse = $sPreResponse->getXML();
				}
				if ($bEndRequest) $sResponse = $sPreResponse;
			}

			if (!$bEndRequest) {
				if (!$this->_isFunctionCallable($sFunctionName)) {
					$objResponse = new xajaxResponse();
					$objResponse->addAlert("The Registered Function $sFunctionName Could Not Be Found.");
					$sResponse = $objResponse->getXML();
				}
				else {
					if ($bFunctionIsCatchAll) {
						$aArgs = array($sFunctionNameForSpecial, $aArgs);
					}
					$sResponse = $this->_callFunction($sFunctionName, $aArgs);
				}
				if (is_a($sResponse, "xajaxResponse")) {
					$sResponse = $sResponse->getXML();
				}
				if (!is_string($sResponse) || strpos($sResponse, "<xjx>") === FALSE) {
					$objResponse = new xajaxResponse();
					$objResponse->addAlert("No XML Response Was Returned By Function $sFunctionName.");
					$sResponse = $objResponse->getXML();
				}
				else if ($sPreResponse != "") {
					$sNewResponse = new xajaxResponse($this->sEncoding, $this->bOutputEntities);
					$sNewResponse->loadXML($sPreResponse);
					$sNewResponse->loadXML($sResponse);
					$sResponse = $sNewResponse->getXML();
				}
			}
		}

		$sContentHeader = "Content-type: text/xml;";
		if ($this->sEncoding && strlen(trim($this->sEncoding)) > 0)
			$sContentHeader .= " charset=".$this->sEncoding;
		header($sContentHeader);
		if ($this->bErrorHandler && !empty( $GLOBALS['xajaxErrorHandlerText'] )) {
			$sErrorResponse = new xajaxResponse();
			$sErrorResponse->addAlert("** PHP Error Messages: **" . $GLOBALS['xajaxErrorHandlerText']);
			if ($this->sLogFile) {
				$fH = @fopen($this->sLogFile, "a");
				if (!$fH) {
					$sErrorResponse->addAlert("** Logging Error **\n\nxajax was unable to write to the error log file:\n" . $this->sLogFile);
				}
				else {
					fwrite($fH, "** xajax Error Log - " . strftime("%b %e %Y %I:%M:%S %p") . " **" . $GLOBALS['xajaxErrorHandlerText'] . "\n\n\n");
					fclose($fH);
				}
			}

			$sErrorResponse->loadXML($sResponse);
			$sResponse = $sErrorResponse->getXML();

		}
		if ($this->bCleanBuffer) while (@ob_end_clean());
		print $sResponse;
		if ($this->bErrorHandler) restore_error_handler();

		if ($this->bExitAllowed)
			exit();
	}

	function printJavascript($sJsURI="", $sJsFile=NULL)
	{
		print $this->getJavascript($sJsURI, $sJsFile);
	}

	function getJavascript($sJsURI="", $sJsFile=NULL)
	{
		$html = $this->getJavascriptConfig();
		$html .= $this->getJavascriptInclude($sJsURI, $sJsFile);

		return $html;
	}

	function getJavascriptConfig()
	{
		$html  = "\t<script type=\"text/javascript\">\n";
		$html .= "var xajaxRequestUri=\"".$this->sRequestURI."\";\n";
		$html .= "var xajaxDebug=".($this->bDebug?"true":"false").";\n";
		$html .= "var xajaxStatusMessages=".($this->bStatusMessages?"true":"false").";\n";
		$html .= "var xajaxWaitCursor=".($this->bWaitCursor?"true":"false").";\n";
		$html .= "var xajaxDefinedGet=".XAJAX_GET.";\n";
		$html .= "var xajaxDefinedPost=".XAJAX_POST.";\n";
		$html .= "var xajaxLoaded=false;\n";

		foreach($this->aFunctions as $sFunction => $bExists) {
			$html .= $this->_wrap($sFunction,$this->aFunctionRequestTypes[$sFunction]);
		}

		$html .= "\t</script>\n";
		return $html;
	}

	function getJavascriptInclude($sJsURI="", $sJsFile=NULL)
	{
		if ($sJsFile == NULL) $sJsFile = "js/xajax.js";

		if ($sJsURI != "" && substr($sJsURI, -1) != "/") $sJsURI .= "/";

		$html = "\t<script type=\"text/javascript\" src=\"" . $sJsURI . $sJsFile . "\"></script>\n";
		$html .= "\t<script type=\"text/javascript\">\n";
		$html .= "window.setTimeout(function () { if (!xajaxLoaded) { alert('Error: the xajax Javascript file could not be included. Perhaps the URL is incorrect?\\nURL: {$sJsURI}{$sJsFile}'); } }, 6000);\n";
		$html .= "\t</script>\n";
		return $html;
	}

	function autoCompressJavascript($sJsFullFilename=NULL)
	{
		$sJsFile = "xajax_js/xajax.js";

		if ($sJsFullFilename) {
			$realJsFile = $sJsFullFilename;
		}
		else {
			$realPath = realpath(dirname(__FILE__));
			$realJsFile = $realPath . "/". $sJsFile;
		}

		if (!file_exists($realJsFile)) {
			$srcFile = str_replace(".js", "_uncompressed.js", $realJsFile);
			if (!file_exists($srcFile)) {
				trigger_error("The xajax uncompressed Javascript file could not be found in the <b>" . dirname($realJsFile) . "</b> folder. Error ", E_USER_ERROR);
			}
			#require(dirname(__FILE__)."/xajax_compress.php");

			$javaScript = implode('', file($srcFile));
			$compressedScript = xajaxCompressJavascript($javaScript);
			$fH = @fopen($realJsFile, "w");
			if (!$fH) {
				trigger_error("The xajax compressed javascript file could not be written in the <b>" . dirname($realJsFile) . "</b> folder. Error ", E_USER_ERROR);
			}
			else {
				fwrite($fH, $compressedScript);
				fclose($fH);
			}
		}
	}

	function _detectURI() {
		$aURL = array();

		if (!empty($_SERVER['REQUEST_URI'])) {
			$aURL = parse_url($_SERVER['REQUEST_URI']);
		}

		if (empty($aURL['scheme'])) {
			if (!empty($_SERVER['HTTP_SCHEME'])) {
				$aURL['scheme'] = $_SERVER['HTTP_SCHEME'];
			} else {
				$aURL['scheme'] = (!empty($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) != 'off') ? 'https' : 'http';
			}
		}

		if (empty($aURL['host'])) {
			if (!empty($_SERVER['HTTP_HOST'])) {
				if (strpos($_SERVER['HTTP_HOST'], ':') > 0) {
					list($aURL['host'], $aURL['port']) = explode(':', $_SERVER['HTTP_HOST']);
				} else {
					$aURL['host'] = $_SERVER['HTTP_HOST'];
				}
			} else if (!empty($_SERVER['SERVER_NAME'])) {
				$aURL['host'] = $_SERVER['SERVER_NAME'];
			} else {
				print "xajax Error: xajax failed to automatically identify your Request URI.";
				print "Please set the Request URI explicitly when you instantiate the xajax object.";
				exit();
			}
		}

		if (empty($aURL['port']) && !empty($_SERVER['SERVER_PORT'])) {
			$aURL['port'] = $_SERVER['SERVER_PORT'];
		}

		if (empty($aURL['path'])) {
			if (!empty($_SERVER['PATH_INFO'])) {
				$sPath = parse_url($_SERVER['PATH_INFO']);
			} else {
				$sPath = parse_url($_SERVER['PHP_SELF']);
			}
			$aURL['path'] = $sPath['path'];
			unset($sPath);
		}

		if (!empty($aURL['query'])) {
			$aURL['query'] = '?'.$aURL['query'];
		}

		$sURL = $aURL['scheme'].'://';
		if (!empty($aURL['user'])) {
			$sURL.= $aURL['user'];
			if (!empty($aURL['pass'])) {
				$sURL.= ':'.$aURL['pass'];
			}
			$sURL.= '@';
		}

		$sURL.= $aURL['host'];

		if (!empty($aURL['port']) && (($aURL['scheme'] == 'http' && $aURL['port'] != 80) || ($aURL['scheme'] == 'https' && $aURL['port'] != 443))) {
			$sURL.= ':'.$aURL['port'];
		}

		if($aURL["query"])
		{
			$sURL.= $aURL['path'].$aURL['query'];
		}
		else
		{
			if($_SERVER["QUERY_STRING"])
			{
				$sURL .= $aURL['path']."?".$_SERVER["QUERY_STRING"];
			}
		}
		unset($aURL);
		return $sURL;
	}

	function _isObjectCallback($sFunction)
	{
		if (array_key_exists($sFunction, $this->aObjects)) return true;
		return false;
	}

	function _isFunctionCallable($sFunction)
	{
		if ($this->_isObjectCallback($sFunction)) {
			if (is_object($this->aObjects[$sFunction][0])) {
				return method_exists($this->aObjects[$sFunction][0], $this->aObjects[$sFunction][1]);
			}
			else {
				return is_callable($this->aObjects[$sFunction]);
			}
		}
		else {
			return function_exists($sFunction);
		}
	}

	function _callFunction($sFunction, $aArgs)
	{
		if ($this->_isObjectCallback($sFunction)) {
			$mReturn = call_user_func_array($this->aObjects[$sFunction], $aArgs);
		}
		else {
			$mReturn = call_user_func_array($sFunction, $aArgs);
		}
		return $mReturn;
	}

	function _wrap($sFunction,$sRequestType=XAJAX_POST)
	{
		$js = "function ".$this->sWrapperPrefix."$sFunction(){return xajax.call(\"$sFunction\", arguments, ".$sRequestType.");}\n";
		return $js;
	}

	function _xmlToArray($rootTag, $sXml)
	{
		$aArray = array();
		$sXml = str_replace("<$rootTag>","<$rootTag>|~|",$sXml);
		$sXml = str_replace("</$rootTag>","</$rootTag>|~|",$sXml);
		$sXml = str_replace("<e>","<e>|~|",$sXml);
		$sXml = str_replace("</e>","</e>|~|",$sXml);
		$sXml = str_replace("<k>","<k>|~|",$sXml);
		$sXml = str_replace("</k>","|~|</k>|~|",$sXml);
		$sXml = str_replace("<v>","<v>|~|",$sXml);
		$sXml = str_replace("</v>","|~|</v>|~|",$sXml);
		$sXml = str_replace("<q>","<q>|~|",$sXml);
		$sXml = str_replace("</q>","|~|</q>|~|",$sXml);

		$this->aObjArray = explode("|~|",$sXml);

		$this->iPos = 0;
		$aArray = $this->_parseObjXml($rootTag);

		return $aArray;
	}

	function _parseObjXml($rootTag)
	{
		$aArray = array();

		if ($rootTag == "xjxobj")
		{
			while(!stristr($this->aObjArray[$this->iPos],"</xjxobj>"))
			{
				$this->iPos++;
				if(stristr($this->aObjArray[$this->iPos],"<e>"))
				{
					$key = "";
					$value = null;

					$this->iPos++;
					while(!stristr($this->aObjArray[$this->iPos],"</e>"))
					{
						if(stristr($this->aObjArray[$this->iPos],"<k>"))
						{
							$this->iPos++;
							while(!stristr($this->aObjArray[$this->iPos],"</k>"))
							{
								$key .= $this->aObjArray[$this->iPos];
								$this->iPos++;
							}
						}
						if(stristr($this->aObjArray[$this->iPos],"<v>"))
						{
							$this->iPos++;
							while(!stristr($this->aObjArray[$this->iPos],"</v>"))
							{
								if(stristr($this->aObjArray[$this->iPos],"<xjxobj>"))
								{
									$value = $this->_parseObjXml("xjxobj");
									$this->iPos++;
								}
								else
								{
									$value .= $this->aObjArray[$this->iPos];
									if ($this->bDecodeUTF8Input)
									{
										$value = $this->_decodeUTF8Data($value);
									}
								}
								$this->iPos++;
							}
						}
						$this->iPos++;
					}

					$aArray[$key]=$value;
				}
			}
		}

		if ($rootTag == "xjxquery")
		{
			$sQuery = "";
			$this->iPos++;
			while(!stristr($this->aObjArray[$this->iPos],"</xjxquery>"))
			{
				if (stristr($this->aObjArray[$this->iPos],"<q>") || stristr($this->aObjArray[$this->iPos],"</q>"))
				{
					$this->iPos++;
					continue;
				}
				$sQuery	.= $this->aObjArray[$this->iPos];
				$this->iPos++;
			}

			parse_str($sQuery, $aArray);
			if ($this->bDecodeUTF8Input)
			{
				foreach($aArray as $key => $value)
				{
					$aArray[$key] = $this->_decodeUTF8Data($value);
				}
			}
			if (get_magic_quotes_gpc() == 1) {
				$newArray = array();
				foreach ($aArray as $sKey => $sValue) {
					if (is_string($sValue))
						$newArray[$sKey] = stripslashes($sValue);
					else
						$newArray[$sKey] = $sValue;
				}
				$aArray = $newArray;
			}
		}

		return $aArray;
	}

	function _decodeUTF8Data($sData)
	{
		$sValue = $sData;
		if ($this->bDecodeUTF8Input)
		{
			$sFuncToUse = NULL;

			if (function_exists('iconv'))
			{
				$sFuncToUse = "iconv";
			}
			else if (function_exists('mb_convert_encoding'))
			{
				$sFuncToUse = "mb_convert_encoding";
			}

			if ($sFuncToUse)
			{
				if (is_string($sValue))
				{
					if ($sFuncToUse == "iconv")
					{
						$sValue = iconv("UTF-8", $this->sEncoding.'//TRANSLIT', $sValue);
					}
					else if ($sFuncToUse == "mb_convert_encoding")
					{
						$sValue = mb_convert_encoding($sValue, $this->sEncoding, "UTF-8");
					}
				}
			}
		}
		return $sValue;
	}

}

function xajaxErrorHandler($errno, $errstr, $errfile, $errline)
{
	$errorReporting = error_reporting();
	if (($errno & $errorReporting) == 0) return;

	if ($errno == E_NOTICE) {
		$errTypeStr = "NOTICE";
	}
	else if ($errno == E_WARNING) {
		$errTypeStr = "WARNING";
	}
	else if ($errno == E_USER_NOTICE) {
		$errTypeStr = "USER NOTICE";
	}
	else if ($errno == E_USER_WARNING) {
		$errTypeStr = "USER WARNING";
	}
	else if ($errno == E_USER_ERROR) {
		$errTypeStr = "USER FATAL ERROR";
	}
	else if ($errno == E_STRICT) {
		return;
	}
	else {
		$errTypeStr = "UNKNOWN: $errno";
	}
	$GLOBALS['xajaxErrorHandlerText'] .= "\n----\n[$errTypeStr] $errstr\nerror in line $errline of file $errfile";
}

function xajaxCompressJavascript($sJS)
{
	$sJS = str_replace("\r","",$sJS);

	$literal_strings = array();

	$lines = explode("\n",$sJS);
	$clean = "";
	$inComment = false;
	$literal = "";
	$inQuote = false;
	$escaped = false;
	$quoteChar = "";

	for($i=0;$i<count($lines);$i++)
	{
		$line = $lines[$i];
		$inNormalComment = false;

		for($j=0;$j<strlen($line);$j++)
		{
			$c = substr($line,$j,1);
			$d = substr($line,$j,2);

			if(!$inQuote && !$inComment)
			{
				if(($c=="\"" || $c=="'") && !$inComment && !$inNormalComment)
				{
					$inQuote = true;
					$inComment = false;
					$escaped = false;
					$quoteChar = $c;
					$literal = $c;
				}
				else if($d=="/*" && !$inNormalComment)
				{
					$inQuote = false;
					$inComment = true;
					$escaped = false;
					$quoteChar = $d;
					$literal = $d;
					$j++;
				}
				else if($d=="//")
				{
					$inNormalComment = true;
					$clean .= $c;
				}
				else
				{
					$clean .= $c;
				}
			}
			else
			{
				if($c == $quoteChar && !$escaped && !$inComment)
				{
					$inQuote = false;
					$literal .= $c;

					$clean .= "___" . count($literal_strings) . "___";

					array_push($literal_strings,$literal);

				}
				else if($inComment && $d=="*/")
				{
					$inComment = false;
					$literal .= $d;

					$clean .= "___" . count($literal_strings) . "___";

					array_push($literal_strings,$literal);

					$j++;
				}
				else if($c == "\\" && !$escaped)
					$escaped = true;
				else
					$escaped = false;

				$literal .= $c;
			}
		}
		if($inComment) $literal .= "\n";
		$clean .= "\n";
	}
	$lines = explode("\n",$clean);

	for($i=0;$i<count($lines);$i++)
	{
		$line = $lines[$i];

		$line = preg_replace("/\/\/(.*)/","",$line);

		$line = trim($line);

		$line = preg_replace("/\s+/"," ",$line);

		$line = preg_replace("/\s*([!\}\{;,&=\|\-\+\*\/\)\(:])\s*/","\\1",$line);

		$lines[$i] = $line;
	}

	$sJS = implode("\n",$lines);

	$sJS = preg_replace("/[\n]+/","\n",$sJS);

	$sJS = preg_replace("/;\n/",";",$sJS);

	$sJS = preg_replace("/[\n]*\{[\n]*/","{",$sJS);

	for($i=0;$i<count($literal_strings);$i++)
		$sJS = str_replace("___".$i."___",$literal_strings[$i],$sJS);

	return $sJS;
}

?>