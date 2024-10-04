<?php

namespace Domain\Models;

/**
 * Class Book
 *
 * Represents a book with its details and availability status.
 */
class Book
{
    private $id;
    private $title;
    private $author;
    private $isbn;
    private $available;

    /**
     * Book constructor.
     *
     * @param string $title The title of the book.
     * @param string $author The author of the book.
     * @param string $isbn The ISBN of the book.
     */
    public function __construct($title, $author, $isbn)
    {
        $this->title = $title;
        $this->author = $author;
        $this->isbn = $isbn;
        $this->available = 1; // 1 means available
    }

    /**
     * Gets the title of the book.
     *
     * @return string The title of the book.
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Gets the author of the book.
     *
     * @return string The author of the book.
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Gets the ISBN of the book.
     *
     * @return string The ISBN of the book.
     */
    public function getISBN()
    {
        return $this->isbn;
    }

    /**
     * Checks if the book is available.
     *
     * @return bool True if the book is available, false otherwise.
     */
    public function isAvailable()
    {
        return $this->available;
    }

    /**
     * Sets the availability status of the book.
     *
     * @param bool $available The availability status of the book.
     */
    public function setAvailable($available)
    {
        $this->available = $available;
    }
}
