<?php
/**
 * Database Configuration
 * Menggunakan OOP untuk koneksi database dengan PDO
 */

class Database {
    private $host = 'localhost';
    private $db_name = 'curupwater_db';
    private $username = 'root';
    private $password = '';
    private $conn;

    /**
     * Mendapatkan koneksi database
     * @return PDO
     */
    public function getConnection() {
        $this->conn = null;

        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=utf8mb4",
                $this->username,
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            echo "Connection Error: " . $e->getMessage();
        }

        return $this->conn;
    }
}
?>
