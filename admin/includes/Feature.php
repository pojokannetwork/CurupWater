<?php
/**
 * Class Feature - Menangani CRUD keunggulan/fitur
 */

require_once __DIR__ . '/../../config/db.php';

class Feature {
    private $conn;
    private $table_name = "features";

    public $id;
    public $title;
    public $description;
    public $icon;
    public $display_order;
    public $is_active;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    /**
     * Membaca semua fitur
     * @return PDOStatement
     */
    public function read() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY display_order ASC, id ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    /**
     * Membaca fitur aktif untuk landing page
     * @return PDOStatement
     */
    public function readActive() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE is_active = 1 ORDER BY display_order ASC, id ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    /**
     * Membaca satu fitur berdasarkan ID
     * @return bool
     */
    public function readOne() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id LIMIT 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            
            $this->title = $row['title'];
            $this->description = $row['description'];
            $this->icon = $row['icon'];
            $this->display_order = $row['display_order'];
            $this->is_active = $row['is_active'];
            
            return true;
        }
        
        return false;
    }

    /**
     * Menambah fitur baru
     * @return bool
     */
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                  (title, description, icon, display_order, is_active) 
                  VALUES (:title, :description, :icon, :display_order, :is_active)";
        
        $stmt = $this->conn->prepare($query);

        // Sanitize
        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->icon = htmlspecialchars(strip_tags($this->icon));
        $this->display_order = htmlspecialchars(strip_tags($this->display_order));
        $this->is_active = htmlspecialchars(strip_tags($this->is_active));

        // Bind
        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':icon', $this->icon);
        $stmt->bindParam(':display_order', $this->display_order);
        $stmt->bindParam(':is_active', $this->is_active);

        if ($stmt->execute()) {
            return true;
        }
        
        return false;
    }

    /**
     * Update fitur
     * @return bool
     */
    public function update() {
        $query = "UPDATE " . $this->table_name . " 
                  SET title = :title, 
                      description = :description, 
                      icon = :icon, 
                      display_order = :display_order, 
                      is_active = :is_active 
                  WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);

        // Sanitize
        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->icon = htmlspecialchars(strip_tags($this->icon));
        $this->display_order = htmlspecialchars(strip_tags($this->display_order));
        $this->is_active = htmlspecialchars(strip_tags($this->is_active));
        $this->id = htmlspecialchars(strip_tags($this->id));

        // Bind
        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':icon', $this->icon);
        $stmt->bindParam(':display_order', $this->display_order);
        $stmt->bindParam(':is_active', $this->is_active);
        $stmt->bindParam(':id', $this->id);

        if ($stmt->execute()) {
            return true;
        }
        
        return false;
    }

    /**
     * Hapus fitur
     * @return bool
     */
    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(':id', $this->id);

        if ($stmt->execute()) {
            return true;
        }
        
        return false;
    }
}
?>
