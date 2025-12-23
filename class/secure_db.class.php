<?php
// class/secure_db.class.php - Secure database class using SQLite

class SecureDB {
    private $pdo;
    private $dbPath;

    public function __construct($dbPath) {
        $this->dbPath = $dbPath;
        $this->connect();
    }

    private function connect() {
        try {
            $this->pdo = new PDO("sqlite:" . $this->dbPath, null, null, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }

    public function select($table, $conditions = [], $params = [], $orderBy = null, $limit = null) {
        $sql = "SELECT * FROM `$table`";
        $whereClause = '';
        
        if (!empty($conditions)) {
            $whereClause = " WHERE " . implode(' AND ', array_map(function($key) {
                return "`$key` = ?";
            }, array_keys($conditions)));
        }
        
        $sql .= $whereClause;
        
        if ($orderBy) {
            $sql .= " ORDER BY $orderBy";
        }
        
        if ($limit) {
            $sql .= " LIMIT $limit";
        }
        
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute(array_values($params ?: $conditions));
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Database select error: " . $e->getMessage());
            return [];
        }
    }

    public function insert($table, $data) {
        $columns = implode(', ', array_map(function($key) {
            return "`$key`";
        }, array_keys($data)));
        
        $placeholders = implode(', ', array_fill(0, count($data), '?'));
        $sql = "INSERT INTO `$table` ($columns) VALUES ($placeholders)";
        
        try {
            $stmt = $this->pdo->prepare($sql);
            $result = $stmt->execute(array_values($data));
            return $result ? $this->pdo->lastInsertId() : false;
        } catch (PDOException $e) {
            error_log("Database insert error: " . $e->getMessage());
            return false;
        }
    }

    public function update($table, $data, $conditions = [], $params = []) {
        $setClause = implode(', ', array_map(function($key) {
            return "`$key` = ?";
        }, array_keys($data)));
        
        $whereClause = '';
        if (!empty($conditions)) {
            $whereClause = " WHERE " . implode(' AND ', array_map(function($key) {
                return "`$key` = ?";
            }, array_keys($conditions)));
        }
        
        $sql = "UPDATE `$table` SET $setClause$whereClause";
        
        try {
            $stmt = $this->pdo->prepare($sql);
            $values = array_values($data);
            if (!empty($params)) {
                $values = array_merge($values, array_values($params));
            } else {
                $values = array_merge($values, array_values($conditions));
            }
            return $stmt->execute($values);
        } catch (PDOException $e) {
            error_log("Database update error: " . $e->getMessage());
            return false;
        }
    }

    public function delete($table, $conditions = [], $params = []) {
        $whereClause = " WHERE " . implode(' AND ', array_map(function($key) {
            return "`$key` = ?";
        }, array_keys($conditions)));
        
        $sql = "DELETE FROM `$table`$whereClause";
        
        try {
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute(!empty($params) ? array_values($params) : array_values($conditions));
        } catch (PDOException $e) {
            error_log("Database delete error: " . $e->getMessage());
            return false;
        }
    }
}
?>
