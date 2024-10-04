<?php

namespace Application\Controllers;

use Domain\Repositories\Database;
use Domain\Models\Loan;

/**
 * Class LoanController
 *
 * Handles operations related to loans, including adding,
 * updating, and retrieving loans.
 */
class LoanController
{
    private $db;

    /**
     * LoanController constructor.
     *
     * @param Database $db The database repository for loan operations.
     */
    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    /**
     * Adds a new loan for a specific book to a user.
     *
     * @param int $book_id The ID of the book being loaned.
     * @param int $user_id The ID of the user borrowing the book.
     */
    public function addLoan($book_id, $user_id)
    {
        $this->db->addLoan($book_id, $user_id);
    }

    /**
     * Updates the details of a specific loan.
     *
     * @param int $loan_id The ID of the loan to update.
     */
    public function updateLoan($loan_id)
    {
        $this->db->updateLoan($loan_id);
    }

    /**
     * Retrieves the books associated with a specific loan.
     *
     * @param int $loan_id The ID of the loan to check.
     * @return array An array of books associated with the loan.
     */
    public function getBooksByLoan($loan_id)
    {
        return $this->db->getBooksByLoan($loan_id);
    }

    /**
     * Retrieves all loans from the database.
     *
     * @return array An array of all loans.
     */
    public function getAllLoan()
    {
        return $this->db->getAllLoan();
    }
}
