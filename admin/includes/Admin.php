<?php
/**
 * Class Admin - Menangani autentikasi dan manajemen admin
 */

require_once __DIR__ . '/../../config/db.php';

class Admin {
    private $conn;
    private $table_name = "admin";

    public $id;
    public $username;
    public $password;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    /**
     * Login admin
     * @param string $username
     * @param string $password
     * @return bool
     */
    public function login($username, $password) {
        $query = "SELECT id, username, password FROM " . $this->table_name . " WHERE username = :username LIMIT 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (password_verify($password, $row['password'])) {
                $this->id = $row['id'];
                $this->username = $row['username'];
                return true;
            }
        }
        
        return false;
    }

    /**
     * Cek apakah admin sudah login
     * @return bool
     */
    public static function isLoggedIn() {
        return isset($_SESSION['admin_id']) && !empty($_SESSION['admin_id']);
    }

    /**
     * Logout admin
     */
    public static function logout() {
        session_destroy();
    }

    /**
     * Update password admin
     * @param int $id
     * @param string $new_password
     * @return bool
     */
    public function updatePassword($id, $new_password) {
        $query = "UPDATE " . $this->table_name . " SET password = :password WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        
        $stmt->bindParam(':password', $hashed_password);
        $stmt->bindParam(':id', $id);

        if ($stmt->execute()) {
            return true;
        }
        
        return false;
    }
}
?>
