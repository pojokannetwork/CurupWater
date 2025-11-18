<?php
/**
 * Class Contact - Menangani info kontak
 */

require_once __DIR__ . '/../../config/db.php';

class Contact {
    private $conn;
    private $table_name = "contact";

    public $id;
    public $phone;
    public $email;
    public $address;
    public $whatsapp;
    public $instagram;
    public $facebook;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    /**
     * Membaca info kontak
     * @return bool
     */
    public function read() {
        $query = "SELECT * FROM " . $this->table_name . " LIMIT 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            
            $this->id = $row['id'];
            $this->phone = $row['phone'];
            $this->email = $row['email'];
            $this->address = $row['address'];
            $this->whatsapp = $row['whatsapp'];
            $this->instagram = $row['instagram'];
            $this->facebook = $row['facebook'];
            
            return true;
        }
        
        return false;
    }

    /**
     * Update info kontak
     * @return bool
     */
    public function update() {
        $query = "UPDATE " . $this->table_name . " 
                  SET phone = :phone, 
                      email = :email, 
                      address = :address, 
                      whatsapp = :whatsapp, 
                      instagram = :instagram, 
                      facebook = :facebook 
                  WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);

        // Sanitize
        $this->phone = htmlspecialchars(strip_tags($this->phone));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->address = htmlspecialchars(strip_tags($this->address));
        $this->whatsapp = htmlspecialchars(strip_tags($this->whatsapp));
        $this->instagram = htmlspecialchars(strip_tags($this->instagram));
        $this->facebook = htmlspecialchars(strip_tags($this->facebook));
        $this->id = htmlspecialchars(strip_tags($this->id));

        // Bind
        $stmt->bindParam(':phone', $this->phone);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':address', $this->address);
        $stmt->bindParam(':whatsapp', $this->whatsapp);
        $stmt->bindParam(':instagram', $this->instagram);
        $stmt->bindParam(':facebook', $this->facebook);
        $stmt->bindParam(':id', $this->id);

        if ($stmt->execute()) {
            return true;
        }
        
        return false;
    }
}
?>
