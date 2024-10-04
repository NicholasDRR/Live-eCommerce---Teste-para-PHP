<?php

namespace Application\Controllers;

use Domain\Repositories\Database;
use Domain\Models\Book;

/**
 * Class BookController
 *
 * Handles the operations related to books, including adding,
 * retrieving, and updating book availability.
 */
class BookController
{
    private $db;

    /**
     * BookController constructor.
     *
     * @param Database $db The database repository for book operations.
     */
    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    /**
     * Adds a new book to the database.
     *
     * @param string $title The title of the book.
     * @param string $author The author of the book.
     * @param string $isbn The ISBN of the book.
     */
    public function addBook($title, $author, $isbn)
    {
        $book = new Book($title, $author, $isbn);
        $this->db->addBook($book->getTitle(), $book->getAuthor(), $book->getISBN());
    }

    /**
     * Retrieves all books from the database.
     *
     * @return array An array of books.
     */
    public function getBooks()
    {
        return $this->db->getBooks();
    }

    /**
     * Checks the availability of a specific book.
     *
     * @param int $book_id The ID of the book to check availability.
     * @return bool The availability status of the book.
     */
    public function getBookAvailability($book_id)
    {
        return $this->db->getBookAvailability($book_id);
    }

    /**
     * Updates the availability status of a specific book.
     *
     * @param int $book_id The ID of the book to update.
     * @param bool $availability The new availability status.
     */
    public function updateBookAvailability($book_id, $availability)
    {
        $this->db->updateBookAvailability($book_id, $availability);
    }
}
