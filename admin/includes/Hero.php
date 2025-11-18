<?php
/**
 * Class Hero - Menangani Hero Section landing page
 */

require_once __DIR__ . '/../../config/db.php';

class Hero {
    private $conn;
    private $table_name = "hero";

    public $id;
    public $title;
    public $subtitle;
    public $button_text;
    public $button_link;
    public $background_image;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    /**
     * Membaca konten Hero
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
            $this->subtitle = $row['subtitle'];
            $this->button_text = $row['button_text'];
            $this->button_link = $row['button_link'];
            $this->background_image = $row['background_image'];
            
            return true;
        }
        
        return false;
    }

    /**
     * Update konten Hero
     * @return bool
     */
    public function update() {
        $query = "UPDATE " . $this->table_name . " 
                  SET title = :title, 
                      subtitle = :subtitle, 
                      button_text = :button_text, 
                      button_link = :button_link, 
                      background_image = :background_image 
                  WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);

        // Sanitize
        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->subtitle = htmlspecialchars(strip_tags($this->subtitle));
        $this->button_text = htmlspecialchars(strip_tags($this->button_text));
        $this->button_link = htmlspecialchars(strip_tags($this->button_link));
        $this->background_image = htmlspecialchars(strip_tags($this->background_image));
        $this->id = htmlspecialchars(strip_tags($this->id));

        // Bind
        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':subtitle', $this->subtitle);
        $stmt->bindParam(':button_text', $this->button_text);
        $stmt->bindParam(':button_link', $this->button_link);
        $stmt->bindParam(':background_image', $this->background_image);
        $stmt->bindParam(':id', $this->id);

        if ($stmt->execute()) {
            return true;
        }
        
        return false;
    }
}
?>
