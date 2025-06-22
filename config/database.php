
<?php
class Database {
    private $host = "localhost";
    private $db_name = "mallroadhouse";
    private $username = "root";
    private $password = "";
    public $conn;

    public function getConnection() {
        $this->conn = null;

        try {
            // First try to connect without specifying database to create it if needed
            $temp_conn = new PDO("mysql:host=" . $this->host, $this->username, $this->password);
            $temp_conn->exec("CREATE DATABASE IF NOT EXISTS " . $this->db_name);
            $temp_conn = null;

            // Now connect to the specific database
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->exec("set names utf8");
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
        } catch(PDOException $exception) {
            error_log("Database connection error: " . $exception->getMessage());
            echo json_encode(['error' => 'Database connection failed: ' . $exception->getMessage()]);
            return null;
        }

        return $this->conn;
    }
}
?>
