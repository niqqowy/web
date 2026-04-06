<?php
class MasterClass {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->createTable();
    }

    private function createTable() {
        $sql = "CREATE TABLE IF NOT EXISTS registrations (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            birth_date DATE NOT NULL,
            topic VARCHAR(100) NOT NULL,
            materials_included TINYINT(1) DEFAULT 0,
            format VARCHAR(50) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";
        $this->pdo->exec($sql);
    }

    public function add($name, $birth_date, $topic, $materials_included, $format) {
        $stmt = $this->pdo->prepare(
            "INSERT INTO registrations (name, birth_date, topic, materials_included, format) 
             VALUES (?, ?, ?, ?, ?)"
        );
        $stmt->execute([$name, $birth_date, $topic, $materials_included, $format]);
    }

    public function getAll($orderBy = 'created_at DESC') {
        $stmt = $this->pdo->query("SELECT * FROM registrations ORDER BY $orderBy");
        return $stmt->fetchAll();
    }

    public function getCount() {
        $stmt = $this->pdo->query("SELECT COUNT(*) as total FROM registrations");
        return $stmt->fetch()['total'];
    }

    public function getByMinAge($minAge) {
        $sql = "SELECT *, TIMESTAMPDIFF(YEAR, birth_date, CURDATE()) as age 
                FROM registrations 
                HAVING age >= ? 
                ORDER BY created_at DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$minAge]);
        return $stmt->fetchAll();
    }
}