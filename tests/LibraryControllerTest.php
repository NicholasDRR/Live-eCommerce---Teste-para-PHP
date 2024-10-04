<?php

use PHPUnit\Framework\TestCase;
use Application\Controllers\BookController;
use Application\Controllers\UserController;
use Application\Controllers\LoanController;
use Domain\Repositories\Database;


// to run this tests: ./vendor/bin/phpunit tests/LibraryControllerTest.php


class LibraryControllerTest extends TestCase
{
    private $database;
    private $bookController;
    private $userController;
    private $loanController;

    protected function setUp(): void
    {
        // Mock the database connection
        $this->database = $this->createMock(Database::class);
        $this->bookController = new BookController($this->database);
        $this->userController = new UserController($this->database);
        $this->loanController = new LoanController($this->database);
    }

    public function testAddBook()
    {
        // Mock the method addBook in BookController
        $this->database->expects($this->once())
            ->method('addBook')
            ->with('Test Title', 'Test Author', '1234567890')
            ->willReturn(true);

        $result = $this->bookController->addBook('Test Title', 'Test Author', '1234567890');
        $this->assertTrue($result);
    }

    public function testAddUser()
    {
        // Mock the method addUser in UserController
        $this->database->expects($this->once())
            ->method('addUser')
            ->with('John Doe', 'john@example.com', 'Student')
            ->willReturn(true);

        $result = $this->userController->addUser('John Doe', 'john@example.com', 'Student');
        $this->assertTrue($result);
    }

    public function testBorrowBook()
    {
        // Mock data
        $userId = 1;
        $bookId = 1;

        // Mock methods in UserController and BookController
        $this->userController->expects($this->once())
            ->method('getBorrowedCount')
            ->with($userId)
            ->willReturn(1);

        $this->userController->expects($this->once())
            ->method('getBookLimit')
            ->with($userId)
            ->willReturn(['books_limit' => 3]);

        $this->bookController->expects($this->once())
            ->method('getBookDisponibility')
            ->with($bookId)
            ->willReturn(['available' => 1]);

        // Test borrowing a book
        $result = $this->loanController->addLoan($bookId, $userId);
        $this->assertTrue($result);
    }

    public function testReturnBook()
    {
        // Mock data
        $loanId = 1;

        // Mock methods in LoanController
        $this->loanController->expects($this->once())
            ->method('updateLoan')
            ->with($loanId)
            ->willReturn(true);

        $result = $this->loanController->updateLoan($loanId);
        $this->assertTrue($result);
    }


}
