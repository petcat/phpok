<?php
#[PDO数据库类 - 修复SQL注入安全漏洞，支持SQLite]
class qgPDO
{
    private $pdo;
    private $queryCount = 0;
    private $queryTimes = 0;

    #[构造函数]
    function __construct($dbhost, $dbdata, $dbuser = "", $dbpass = "", $charset = "utf8")
    {
        // 检查是否为SQLite数据库（通过.db或.sqlite扩展名判断）
        if (preg_match('/\.db$|\.sqlite$|\.db3$|:memory:/i', $dbdata)) {
            $dsn = "sqlite:{$dbdata}";
            try {
                $this->pdo = new PDO($dsn, null, null, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]);
            } catch (PDOException $e) {
                die("SQLite数据库连接失败: " . $e->getMessage());
            }
        } else {
            // 使用MySQL连接
            $dsn = "mysql:host={$dbhost};dbname={$dbdata};charset={$charset}";
            try {
                $this->pdo = new PDO($dsn, $dbuser, $dbpass, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]);
            } catch (PDOException $e) {
                die("数据库连接失败: " . $e->getMessage());
            }
        }
    }

    #[执行预处理查询]
    public function qgQuery($sql, $params = [])
    {
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            $this->queryCount++;
            return $stmt;
        } catch (PDOException $e) {
            error_log("SQL执行错误: " . $e->getMessage() . " - SQL: " . $sql);
            return false;
        }
    }

    #[获取所有结果]
    public function qgGetAll($sql, $params = [])
    {
        $stmt = $this->qgQuery($sql, $params);
        if ($stmt) {
            return $stmt->fetchAll();
        }
        return [];
    }

    #[获取单行结果]
    public function qgGetOne($sql, $params = [])
    {
        $stmt = $this->qgQuery($sql, $params);
        if ($stmt) {
            return $stmt->fetch();
        }
        return false;
    }

    #[执行插入操作]
    public function qgInsert($sql, $params = [])
    {
        $stmt = $this->qgQuery($sql, $params);
        if ($stmt) {
            return $this->pdo->lastInsertId();
        }
        return false;
    }

    #[执行更新/删除操作]
    public function qgExec($sql, $params = [])
    {
        $stmt = $this->qgQuery($sql, $params);
        if ($stmt) {
            return $stmt->rowCount();
        }
        return false;
    }

    #[获取查询计数]
    public function getQueryCount()
    {
        return $this->queryCount;
    }

    #[获取PDO实例]
    public function getPdo()
    {
        return $this->pdo;
    }
}
?>