<?php
#------------------------------------------------------------------------------
#[谢您使用情感家园企业站程序：qgweb]
#[本程序由情感开发完成，当前版本：5.0]
#[本程序基于LGPL授权发布]
#[如果您使用正式版，请将授权文件用FTP上传至copyright目录中]
#[官方网站：www.phpok.com   www.qinggan.net]
#[客服邮箱：qinggan@188.com]
#[文件：request.php]
#------------------------------------------------------------------------------

#[类upload]
class UPLOAD
{
	var $path;
	var $type = ".jpg,.png,.gif,.rar,.zip";

	function UPLOAD($path,$type="png,jpg,gif,rar,zip")
	{
		$this->path = $path;
		//更新文件类型
		$this->type = $this->set_type($type);
	}

	function set_type($type="png,jpg,gif,rar,zip,gz")
	{
		if(!$type)
		{
			$type = "png,jpg,gif,rar,zip,gz";
		}
		$type_array = explode(",",$type);
		$array = array();
		foreach($type_array as $key=>$value)
		{
			$value = trim($value);
			if(strlen($value)>1)
			{
				if((substr($value,0,1) != "."))
				{
					$value = ".".$value;
				}
				$array[$key] = $value;
			}
		}
		$this->type = implode(",",$array);
		$mytype = $this->type;
		return $mytype;
	}

	function up($var,$file="")
	{
		if(empty($var))
		{
			return false;
		}
		$file_name = $this->_check($file);
		if(empty($file_name)) $file_name = time();//如果文件名为空，刚使用时间作为文件名称
		//检查文件名称是否含有后缀，有则去掉
		$file_name = strtolower($file_name);//将所有大写改为小写
		//-----
		$file_type = $this->_file_type($var);
		if($file_type)
		{
			if(strpos($file_name,".") === false)
			{
				$filename = $file_name.$file_type;//新的文件名
			}
			else
			{
				$filename = $file_name;
			}
			#[由于PHP不支持客户端检查文件大小，固这里没有对文件大小进行限制]
			#[情感企业站程序在后台主要是在客户端对上传进行大小限制！]
			$up = @copy($_FILES[$var]["tmp_name"],$this->path.$filename);
			if($up)
			{
				return $filename;
			}
			else
			{
				return false;
			}
		}
		else
		{
			return false;
		}
	}

	function name($var)
	{
		return $_FILES[$var]["name"];
	}

	function _file_type($var)
	{
		if($_FILES[$var]["name"])
		{
			$name = explode(".",$_FILES[$var]["name"]);
			$count = count($name);
			$type = ".".strtolower($name[$count-1]);
			if(strpos($this->type,$type) === false)
			{
				return false;
			}
			return $type;
		}
		else
		{
			return false;
		}
	}

	function _check($file="")
	{
		if($file)
		{
			$array = explode("/",$file);
			$path = "";
			$count = count($array);
			if($count > 1)
			{
				for($i = 0;$i < ($count-1); $i++)
				{
					$path .= $array[$i]."/";
				}
			}
			$file_key = $count - 1;
			$return_file = basename($array[$file_key]);
			if((substr($file,0,1) == "/") || (substr($file,1,2) == ":"))
			{
				$this->_make_dir($path);
				$this->path = $path;//更新新路径
			}
			else
			{
				$this->_make_dir($this->path.$path);
				$this->path = $this->path.$path;
			}
			return $return_file;
		}
		else
		{
			return false;
		}
	}

	#[创建目录]
	function _make_dir($folder)
	{
		$array = explode("/",$folder);
		$count = count($array);
		$msg = "";
		for($i=0;$i<$count;$i++)
		{
			$msg .= $array[$i];
			if(!file_exists($msg) && ($array[$i]))
			{
				mkdir($msg,0777);
			}
			$msg .= "/";
		}
		return true;
	}

	function getpath()
	{
		return $this->path;
	}

	function qgfiletype($filename)
	{
		$filename = basename($filename);
		$name = explode(".",$filename);
		$count = count($name);
		$type = strtolower($name[$count-1]);
		return $type;
	}
}
?>