<?php

namespace App;

class DatabaseConnection{
    private string $dbHost;
    private string $dbName;
    private string $username;
    private string $password;
    public \PDO $db;

    public function __construct(string $envFilePath)
    {
        
        $this->loadEnv($envFilePath);
        $this->dbHost = getenv('DB_HOST');
        $this->dbName = getenv('DB_NAME');
        $this->username = getenv('DB_USER');
        $this->password = getenv('DB_PASS');
        
    }
    
    public function connect():void
    {
        try{
        $pdo = new \PDO("mysql:host=$this->dbHost;dbname=$this->dbName;charset=utf8mb4",$this->username,$this->password);
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
        $this->db = $pdo;
        }
        catch (\PDOException $e) {
            echo "Chyba připojení k databázi: " . $e->getMessage();
        }
    }

    private function loadEnv(string $envFilePath):void
    {
        
        if(!file_exists($envFilePath))
        {
            throw new \Exception(".env file not foung at: $envFilePath");
        }

        $lines = file($envFilePath,FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach($lines as $line)
        {
            if (str_starts_with(trim($line), '#')) continue;
            [$key, $value] = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value);
            putenv("$key=$value");
        }
    }
}