<?php

namespace Domain\Repositories;

use PDO;

/**
 * Class Database
 *
 * Handles the database connection and operations related to books, users, and loans.
 */
class Database
{
    private $db;

    /**
     * Database constructor.
     * Initializes the database connection.
     */
    public function __construct()
    {
        $this->db = new PDO('sqlite:' . __DIR__ . '/../../library.db');
    }

    /**
     * Gets the PDO connection.
     *
     * @return PDO The PDO connection.
     */
    public function getConnection()
    {
        return $this->db;
    }

    /**
     * Creates the necessary tables in the database.
     */
    public function createTables()
    {
        // Creating the books table
        $this->db->exec("CREATE TABLE IF NOT EXISTS books (
            id INTEGER PRIMARY KEY,
            title TEXT NOT NULL,
            author TEXT NOT NULL,
            isbn TEXT NOT NULL,
            available INTEGER NOT NULL DEFAULT 1
        )");

        // Creating the users table
        $this->db->exec("CREATE TABLE IF NOT EXISTS users (
            id INTEGER PRIMARY KEY,
            name TEXT NOT NULL,
            email TEXT NOT NULL,
            role TEXT NOT NULL,
            books_limit INTEGER NOT NULL
        )");

        // Creating the loans table
        $this->db->exec("CREATE TABLE IF NOT EXISTS loans (
            id INTEGER PRIMARY KEY,
            book_id INTEGER,
            user_id INTEGER,
            loan_date TEXT,
            return_date TEXT,
            FOREIGN KEY(book_id) REFERENCES books(id),
            FOREIGN KEY(user_id) REFERENCES users(id)
        )");
    }

    /**
     * Adds a book to the database.
     *
     * @param string $title The title of the book.
     * @param string $author The author of the book.
     * @param string $isbn The ISBN of the book.
     */
    public function addBook($title, $author, $isbn)
    {
        $stmt = $this->db->prepare("INSERT INTO books (title, author, isbn) VALUES (:title, :author, :isbn)");
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':author', $author);
        $stmt->bindParam(':isbn', $isbn);
        $stmt->execute();
    }

    /**
     * Updates the availability status of a book.
     *
     * @param int $book_id The ID of the book.
     * @param int $dispobility The availability status (1 for available, 0 for not available).
     */
    public function updateBookAvailability($book_id, $dispobility)
    {
        $stmt = $this->db->prepare("UPDATE books SET available = :dispobility WHERE id = :book_id");
        $stmt->bindParam(':book_id', $book_id);
        $stmt->bindParam(':dispobility', $dispobility);
        $stmt->execute();
    }

    /**
     * Adds a user to the database.
     *
     * @param string $name The name of the user.
     * @param string $email The email of the user.
     * @param string $role The role of the user (e.g., Student, Teacher).
     */
    public function addUser($name, $email, $role)
    {
        $booksLimit = ($role === 'Student') ? 1 : 2;
        $stmt = $this->db->prepare("INSERT INTO users (name, email, role, books_limit) VALUES (:name, :email, :role, :books_limit)");
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':role', $role);
        $stmt->bindParam(':books_limit', $booksLimit);
        $stmt->execute();
    }

    /**
     * Adds a loan record to the database.
     *
     * @param int $book_id The ID of the book being loaned.
     * @param int $user_id The ID of the user taking the loan.
     */
    public function addLoan($book_id, $user_id)
    {
        $stmt = $this->db->prepare("INSERT INTO loans (book_id, user_id, loan_date) VALUES (:book_id, :user_id, datetime('now'))");
        $stmt->bindParam(':book_id', $book_id);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
    }

    /**
     * Updates the return date of a loan.
     *
     * @param int $loan_id The ID of the loan.
     */
    public function updateLoan($loan_id)
    {
        $stmt = $this->db->prepare("UPDATE loans SET return_date = datetime('now') WHERE id = :loan_id");
        $stmt->bindParam(':loan_id', $loan_id);
        $stmt->execute();
    }

    /**
     * Gets the count of borrowed books for a user.
     *
     * @param int $user_id The ID of the user.
     * @return int The count of borrowed books.
     */
    public function getBorrowedCount($user_id)
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) AS borrowed_count FROM loans WHERE user_id = :user_id AND return_date IS NULL");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['borrowed_count'];
    }

    /**
     * Gets the book limit for a user.
     *
     * @param int $user_id The ID of the user.
     * @return array The book limit of the user.
     */
    public function getBookLimit($user_id)
    {
        $stmt = $this->db->prepare("SELECT books_limit FROM users WHERE id = :user_id");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Gets the availability status of a book.
     *
     * @param int $book_id The ID of the book.
     * @return array The availability status of the book.
     */
    public function getBookAvailability($book_id)
    {
        $stmt = $this->db->prepare("SELECT available FROM books WHERE id = :book_id");
        $stmt->bindParam(':book_id', $book_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Gets all books from the database.
     *
     * @return array An array of all books.
     */
    public function getBooks()
    {
        return $this->db->query("SELECT * FROM books")->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Gets the book IDs associated with a loan.
     *
     * @param int $loan_id The ID of the loan.
     * @return array The book IDs associated with the loan.
     */
    public function getBooksByLoan($loan_id)
    {
        $stmt = $this->db->prepare("SELECT book_id FROM loans WHERE id = :loan_id");
        $stmt->bindParam(':loan_id', $loan_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Gets all users from the database.
     *
     * @return array An array of all users.
     */
    public function getUsers()
    {
        return $this->db->query("SELECT * FROM users")->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Gets all loans from the database.
     *
     * @return array An array of all loans.
     */
    public function getLoans()
    {
        return $this->db->query("SELECT * FROM loans")->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Gets all active loans, including user and book details.
     *
     * @return array An array of active loans with user and book details.
     */
    public function getAllLoan()
    {
        return $this->db->query("
            SELECT l.id, u.name, u.email, b.title, l.loan_date 
            FROM loans l 
            JOIN users u ON l.user_id = u.id 
            JOIN books b ON l.book_id = b.id 
            WHERE l.return_date IS NULL
        ")->fetchAll(PDO::FETCH_ASSOC);
    }
}
