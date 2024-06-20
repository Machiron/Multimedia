<?php
class Connect
{
    const DNS = 'mysql:host=localhost;dbname=machi;charset=utf8mb4';
    const USER = 'root';
    const PASSWORD = '';
    public $pdo = null;

    public function __construct()
    {
        try {
            $this->pdo = new PDO(self::DNS, self::USER, self::PASSWORD);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

}