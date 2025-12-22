<?php
#[无限等级分类的实现]
Class Category
{
	#[要实现无限级分类的数组]
	var $arrayCategory = array();

	var $qglist = array();

	var $sonidlist = array();

	Function __construct()
	{
		//
	}

	#[兼容PHP4]
	Function Category()
	{
		#
	}

	#[排列]
	Function arraySet($array,$parentid=0,$space="")
	{
		foreach($array AS $key=>$value)
		{
			if($parentid == $value["parentid"])
			{
				$value["space"] = $space;
				$this->arrayCategory[] = $value;
				unset($array[$key]);
				$this->arraySet($array,$value["id"],$space."&nbsp; &nbsp; ");
			}
		}
		return $this->arrayCategory;
	}

	Function menuList($array,$cateid,$max=9999)
	{
		foreach($array AS $key=>$value)
		{
			if($value["id"] == $cateid)
			{
				$this->qglist[$max] = $value;
				$m = $max - 1;
				$this->menuList($array,$value["parentid"],$m);
			}
		}
		return $this->qglist;
	}

	Function GetSonIdList($array,$cateid=0,$hidden=true)
	{
		foreach($array AS $key=>$value)
		{
			if($value["parentid"] == $cateid)
			{
				if($hidden)
				{
					if($value["status"])
					{
						$this->sonidlist[$value["id"]] = $value["id"];
						$this->GetSonIdList($array,$value["id"]);
					}
				}
				else
				{
					$this->sonidlist[$value["id"]] = $value["id"];
					$this->GetSonIdList($array,$value["id"],false);
				}
			}
		}
		return $this->sonidlist;
	}
}
?>