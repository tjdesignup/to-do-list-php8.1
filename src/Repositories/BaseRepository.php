<?php 

namespace App\Repositories;
use DateTime;


class BaseRepository{
    
    protected \App\DatabaseConnection $db;

    public function __construct(\App\DatabaseConnection $dbConnection)
    {
        $dbConnection->connect();
        $this->db = $dbConnection;
    }

    private function executeStatement(\PDOStatement $stmt,array $params,string $exception = "Database error."):mixed
    {
        try {
            $stmt->execute($params);
            return $stmt;
        }catch (\Throwable $e){
            error_log("DB error: " . $e->getMessage());
            throw new \Exception($exception);
        }
    }
    
    public function register(string $email,string $password):void
    {    
        $stmt = $this->db->db->prepare("INSERT INTO users (email, pass) VALUES (:email, :pass)");
        $this->executeStatement($stmt,[
                ':email' => $email,
                ':pass' => $password],
                "Email is already register");
    }

    public function unregister(int $id):void
    {    
        $stmt = $this->db->db->prepare("DELETE FROM users WHERE id = :id");
        $stmt = $this->executeStatement($stmt,['id' => $id]);
    }

    private function getUserColumnByCondition(string $column, string $condition, string $value): ?string
    {
        $allowedColumns = ['id', 'email', 'pass'];
        if (!in_array($column, $allowedColumns) || !in_array($condition, $allowedColumns)) {
            throw new \InvalidArgumentException("Invalid column name.");
        }
        $stmt = $this->db->db->prepare("SELECT {$column} FROM users WHERE {$condition} = :email LIMIT 1");
        $stmt = $this->executeStatement($stmt,['email' => $value]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $row[$column] ?? null;
    }

    private function fetchNotesByUserId(int $userId): ?array
    {
        $stmt = $this->db->db->prepare("SELECT * FROM notes WHERE user_id = :user_id ORDER BY id DESC");
        try{
            $stmt->execute(['user_id' => $userId]); 
        }catch(\PDOException $e) {
            throw new \PDOException("Database error: " . $e->getMessage(), (int)$e->getCode());
        }
        $array = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return $array ?? null;
    }

    public function getNoteById(int $id): ?array
    {
        $stmt = $this->db->db->prepare("SELECT * FROM notes WHERE id = :id LIMIT 1");
        try{
            $stmt->execute(['id' => $id]); 
        }catch(\PDOException $e) {
            throw new \PDOException("Database error: " . $e->getMessage(), (int)$e->getCode());
        }
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $row ?? null;
    }

    public function updateNotebyId(int $id, string $title, string $deadline, string $content): void
    {
        if ($deadline === '') $deadline = null;
        $stmt = $this->db->db->prepare("UPDATE notes SET title = :title, content = :content, deadline = :deadline WHERE id = :id");
        try{
            $stmt->execute([
                'title' => $title,
                'content' => $content,
                'deadline' => $deadline,
                'id' => $id
        ]); 
        }catch(\PDOException $e) {
            throw new \PDOException("Database error: " . $e->getMessage(), (int)$e->getCode());
        }
    }

    public function userExists(string $email):bool
    {
        return $this->getUserColumnByCondition('id','email',$email)!==null;
    }

    public function getHashPasswordByEmail(string $email):string
    {
        return $this->getUserColumnByCondition('pass','email',$email);
    }

    public function getUserEmail(int $userId):string
    {
        return $this->getUserColumnByCondition('email','id',$userId);
    }

    public function getUserId(string $email): string
    {
        return $this->getUserColumnByCondition('id','email',$email);
    }

    public function getPaginatedNotesByUserId(int $userId,int $pageNumber): ?array
    {
        $allOrderByNotes = $this->fetchNotesByUserId($userId);
        if(empty($allOrderByNotes)) return [];
        $perPage = 100;
        $offset = ($pageNumber - 1) * $perPage;
        return array_slice($allOrderByNotes, $offset, $perPage);
    }

    public function saveNote(int $userId,string $title,string $text,string $deadline): void
    {
        $now = new DateTime();
        $createdAt =$now->format('Y-m-d');
        if ($deadline === '') $deadline = null;
        $stmt = $this->db->db->prepare("INSERT INTO notes (created_at, title, content, user_id, deadline) 
                                        VALUES (:created_at, :title, :content, :user_id, :deadline)");
        try {
        $stmt->execute([
            ':created_at' => $createdAt,
            ':title' => $title,
            ':content' => $text,
            ':user_id' => $userId,
            ':deadline' => $deadline
        ]);
        } catch (\PDOException $e) {
            throw new \PDOException("Database error: " . $e->getMessage(), (int)$e->getCode());
        }
    }

    public function deleteAllNotes(int $userId): void
    {
        $stmt = $this->db->db->prepare("DELETE FROM notes WHERE user_id = :user_id");
        try {
        $stmt->execute([
            ':user_id' => $userId
        ]);
        } catch (\PDOException $e) {
            throw new \PDOException("Database error: " . $e->getMessage(), (int)$e->getCode());
        }
    }
}