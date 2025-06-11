<?php
namespace App\includes;
use PDO;
use PDOException;

class Database
{
    private static $instance = null;
    private $pdo;
    private $config;

    private function __construct()
    {
        $this->config = require __DIR__ . '/../config.php';

        try {
           
            $dbname = $this->config['db']['database']??'artcommu';
            
            $this->pdo = new PDO(
                "mysql:host={$this->config['db']['host']};port={$this->config['db']['port']};dbname={$dbname};charset={$this->config['db']['charset']}",
                $this->config['db']['username'],
                $this->config['db']['password'],
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false
                ]
            );
        } catch (PDOException $e) {
            error_log('Database connection error: ' . $e->getMessage());
            die("Connection failed: " . $e->getMessage());
        }
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance->pdo;
    }
}
