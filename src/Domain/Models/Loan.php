<?php

namespace Domain\Models;

/**
 * Class Loan
 *
 * Represents a loan transaction for a book, including the user,
 * loan date, and return date.
 */
class Loan
{
    private $book;
    private $user;
    private $loanDate;
    private $returnDate;

    /**
     * Loan constructor.
     *
     * @param Book $book The book being loaned.
     * @param User $user The user who is borrowing the book.
     */
    public function __construct($book, $user)
    {
        $this->book = $book;
        $this->user = $user;
        $this->loanDate = date('Y-m-d');
        $this->returnDate = null;
    }

    /**
     * Marks the book as returned and sets the return date.
     */
    public function returnBook()
    {
        $this->returnDate = date('Y-m-d');
        $this->book->setAvailable(1); // Set book as available
    }

    /**
     * Gets the book associated with the loan.
     *
     * @return Book The book being loaned.
     */
    public function getBook()
    {
        return $this->book;
    }

    /**
     * Gets the user associated with the loan.
     *
     * @return User The user borrowing the book.
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Gets the loan date.
     *
     * @return string The date the loan was made.
     */
    public function getLoanDate()
    {
        return $this->loanDate;
    }

    /**
     * Gets the return date of the loan.
     *
     * @return string|null The return date or null if not returned.
     */
    public function getReturnDate()
    {
        return $this->returnDate;
    }
}
