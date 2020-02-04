<?php

class DB
{
    private $host;
    private $port;
    private $db;
    private $user;
    private $pass;
    private $charset;
    private $options;
    private $pdo;

    public function __construct()
    {
        require_once __DIR__ . '/../vendor/autoload.php';
        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
        $dotenv->load();

        $this->host = getenv("DB_ADDRESS");
        $this->port = getenv("DB_PORT");
        $this->db = getenv("DB_NAME");
        $this->user = getenv("DB_USER");
        $this->pass = getenv("DB_PASSWORD");
        $this->charset = 'utf8mb4';
        }

        public function getDB()
        {
            $this->options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];

            $this->dsn = "mysql:host=$this->host;port=$this->port;dbname=$this->db;charset=$this->charset";

            try {
                $this->pdo = new PDO($this->dsn, $this->user, $this->pass, $this->options);
                return $this->pdo;
            } catch (\PDOException $e) {
                throw new \PDOException($e->getMessage(), (int) $e->getCode());
            }
        }
}