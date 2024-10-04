<?php 
namespace Infrastructure\Database;

use PDO;
use PDOException;

class Database {
    private $connection;

    public function __construct() {
        try {
            // Configurações de conexão
            $this->connection = new PDO('sqlite:' . __DIR__ . '/library.db', null, null, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
        } catch (PDOException $e) {
            // Handle connection errors
            echo "Connection failed: " . $e->getMessage();
            exit;
        }
    }

    public function getConnection() {
        return $this->connection;
    }

    public function createTables() {
        try {
            // Criar tabelas
            $this->connection->exec("
                CREATE TABLE IF NOT EXISTS books (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    title TEXT NOT NULL,
                    author TEXT NOT NULL,
                    isbn TEXT NOT NULL,
                    available INTEGER DEFAULT 1
                );
                CREATE TABLE IF NOT EXISTS users (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    name TEXT NOT NULL,
                    email TEXT NOT NULL,
                    role TEXT NOT NULL,
                    books_limit INTEGER NOT NULL
                );
                CREATE TABLE IF NOT EXISTS loans (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    book_id INTEGER NOT NULL,
                    user_id INTEGER NOT NULL,
                    loan_date DATETIME NOT NULL,
                    return_date DATETIME,
                    FOREIGN KEY (book_id) REFERENCES books (id),
                    FOREIGN KEY (user_id) REFERENCES users (id)
                );
            ");
        } catch (PDOException $e) {
            // Handle table creation errors
            echo "Error creating tables: " . $e->getMessage();
        }
    }
}
