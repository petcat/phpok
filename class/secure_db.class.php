<?php
#------------------------------------------------------------------------------
#[安全数据库类]
#------------------------------------------------------------------------------

#[类库sql - 安全版本]
class SecureDB
{
	var $queryCount = 0;
	var $host;
	var $user;
	var $pass;
	var $data;
	var $conn;
	var $result;
	var $rsType = MYSQLI_ASSOC;
	var $queryTimes = 0;#[查询时间]

	#[构造函数]
	function SecureDB($dbhost,$dbdata,$dbuser="",$dbpass="",$dbOpenType=false)
	{
		$this->host = $dbhost;
		$this->user = $dbuser;
		$this->pass = $dbpass;
		$this->data = $dbdata;
		$this->connect($dbOpenType);
		unset($dbhost,$dbdata,$dbuser,$dbpass,$dbOpenType);
	}

	#[兼容PHP5]
	function __construct($dbhost,$dbdata,$dbuser="",$dbpass="",$dbOpenType=false)
	{
		$this->SecureDB($dbhost,$dbdata,$dbuser,$dbpass,$dbOpenType);
		unset($dbhost,$dbdata,$dbuser,$dbpass,$dbOpenType);
	}

	#[连接数据库]
	function connect($dbconn = false)
	{
		if($dbconn)
		{
			$this->conn = new mysqli($this->host, $this->user, $this->pass, $this->data);
		}
		else
		{
			$this->conn = new mysqli($this->host, $this->user, $this->pass, $this->data);
		}

		if ($this->conn->connect_error) {
			die('数据库连接失败: ' . $this->conn->connect_error);
		}
		
		$this->conn->set_charset("utf8");
	}

	#[关闭数据库连接]
	function qgClose()
	{
		return $this->conn->close();
	}

	#[兼容PHP5]
	function __destruct()
	{
		if ($this->conn) {
			$this->conn->close();
		}
	}

	#[安全查询方法，使用预处理语句]
	function prepareQuery($sql, $params = array())
	{
		$stmt = $this->conn->prepare($sql);
		if ($stmt === false) {
			return false;
		}
		
		if (!empty($params)) {
			$types = '';
			$values = array();
			
			foreach ($params as $param) {
				if (is_int($param)) {
					$types .= 'i';
				} elseif (is_float($param)) {
					$types .= 'd';
				} else {
					$types .= 's';
				}
				$values[] = $param;
			}
			
			$stmt->bind_param($types, ...$values);
		}
		
		$stmt->execute();
		$result = $stmt->get_result();
		
		$this->queryCount++;
		
		if ($result) {
			return $result;
		} else {
			return false;
		}
	}

	function qgQuery($sql,$type="ASSOC")
	{
		$this->rsType = $type != "ASSOC" ? ($type == "NUM" ? MYSQLI_NUM : MYSQLI_BOTH) : MYSQLI_ASSOC;
		$this->result = $this->conn->query($sql);
		$this->queryCount++;
		if($this->result)
		{
			return $this->result;
		}
		else
		{
			return false;
		}
	}

	function qgBigQuery($sql,$type="ASSOC")
	{
		$this->rsType = $type != "ASSOC" ? ($type == "NUM" ? MYSQLI_NUM : MYSQLI_BOTH) : MYSQLI_ASSOC;
		$this->result = $this->conn->query($sql);
		$this->queryCount++;
		if($this->result)
		{
			return $this->result;
		}
		else
		{
			return false;
		}
	}

	function qgGetAll($sql="",$nocache=false)
	{
		if($sql)
		{
			if($nocache)
			{
				$this->qgBigQuery($sql);
			}
			else
			{
				$this->qgQuery($sql);
			}
		}
		$rs = array();
		while($rows = $this->result->fetch_array($this->rsType))
		{
			$rs[] = $rows;
		}
		return $rs;
	}

	function qgGetOne($sql = "")
	{
		if($sql)
		{
			$this->qgQuery($sql);
		}
		$rows = $this->result->fetch_array($this->rsType);
		return $rows;
	}

	function qgInsertID($sql="")
	{
		if($sql)
		{
			$rs = $this->qgGetOne($sql);
			return $rs;
		}
		else
		{
			return $this->conn->insert_id;
		}
	}

	function qgInsert($sql)
	{
		$this->result = $this->qgQuery($sql);
		$id = $this->qgInsertID();
		return $id;
	}

	function qg_count($sql="")
	{
		if($sql)
		{
			$this->qgQuery($sql,"NUM");
			$rs = $this->qgGetOne();
			return $rs[0];
		}
		else
		{
			$rsC = $this->result->num_rows;
			return $rsC;
		}
	}

	function qgCount($sql = "")
	{
		if($sql)
		{
			$this->qgQuery($sql);
			unset($sql);
		}
		$rsC = $this->result->num_rows;
		return $rsC;
	}

	function qgNumFields($sql = "")
	{
		if($sql)
		{
			$this->qgQuery($sql);
		}
		return $this->result->field_count;
	}

	function qgEscapeString($char)
	{
		if(!$char)
		{
			return false;
		}
		return $this->conn->real_escape_string($char);
	}

	function get_mysql_version()
	{
		return $this->conn->server_info;
	}
}
?>