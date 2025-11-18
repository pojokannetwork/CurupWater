<?php
/**
 * Class About - Menangani konten Tentang Kami
 */

require_once __DIR__ . '/../../config/db.php';

class About {
    private $conn;
    private $table_name = "about";

    public $id;
    public $title;
    public $content;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    /**
     * Membaca konten About
     * @return bool
     */
    public function read() {
        $query = "SELECT * FROM " . $this->table_name . " LIMIT 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            
            $this->id = $row['id'];
            $this->title = $row['title'];
            $this->content = $row['content'];
            
            return true;
        }
        
        return false;
    }

    /**
     * Update konten About
     * @return bool
     */
    public function update() {
        $query = "UPDATE " . $this->table_name . " 
                  SET title = :title, 
                      content = :content 
                  WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);

        // Sanitize
        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->content = htmlspecialchars(strip_tags($this->content));
        $this->id = htmlspecialchars(strip_tags($this->id));

        // Bind
        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':content', $this->content);
        $stmt->bindParam(':id', $this->id);

        if ($stmt->execute()) {
            return true;
        }
        
        return false;
    }
}
?>
