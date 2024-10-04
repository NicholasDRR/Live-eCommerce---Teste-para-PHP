<?php

require_once '../../vendor/autoload.php';
require_once __DIR__ . '/../Domain/Repositories/Database.php';
require_once __DIR__ . '/../Application/Controllers/BookController.php';
require_once __DIR__ . '/../Application/Controllers/UserController.php';
require_once __DIR__ . '/../Application/Controllers/LoanController.php';

use Domain\Repositories\Database;
use Application\Controllers\BookController;
use Application\Controllers\UserController;
use Application\Controllers\LoanController;

// Initialize database connection and create tables
$database = new Database();
$db = $database->getConnection();
$database->createTables();

// Initialize controllers
$bookController = new BookController($database);
$userController = new UserController($database);
$loanController = new LoanController($database);

// Handle POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_book'])) {
        $title = $_POST['title'];
        $author = $_POST['author'];
        $isbn = $_POST['isbn'];
        $bookController->addBook($title, $author, $isbn);
    }

    if (isset($_POST['add_user'])) {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $role = $_POST['role'];
        $userController->addUser($name, $email, $role);
    }

    if (isset($_POST['borrow_book'])) {
        $bookId = $_POST['book_id'];
        $userId = $_POST['user_id'];
        $borrowed = $userController->getBorrowedCount($userId);
        $user = $userController->getBookLimit($userId);

        if ($borrowed >= $user['books_limit']) {
            echo "The user has reached their borrowing limit!";
        } else {
            $book = $bookController->getBookAvailability($bookId);

            if ($book['available'] == 0) {
                echo "The book is currently unavailable!";
            } else {
                $loanController->addLoan($bookId, $userId);
                $bookController->updateBookAvailability($bookId, 0);
            }
        }
    }

    if (isset($_POST['return_book'])) {
        $loanId = $_POST['loan_id'];
        $loanController->updateLoan($loanId);
        $loan = $loanController->getBooksByLoan($loanId);
        $bookController->updateBookAvailability($loan['book_id'], 1);
    }
}

// Fetch data for books, users, and loans
$books = $bookController->getBooks();
$users = $userController->getUsers();
$loans = $loanController->getAllLoan();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Management</title>
</head>
<body>
    <h1>Library Management System</h1>

    <h2>Add Book</h2>
    <form method="POST">
        <input type="text" name="title" placeholder="Title" required>
        <input type="text" name="author" placeholder="Author" required>
        <input type="text" name="isbn" placeholder="ISBN" required>
        <button type="submit" name="add_book">Add Book</button>
    </form>

    <h2>Add User</h2>
    <form method="POST">
        <input type="text" name="name" placeholder="Name" required>
        <input type="email" name="email" placeholder="Email" required>
        <select name="role">
            <option value="Student">Student</option>
            <option value="Teacher">Teacher</option>
        </select>
        <button type="submit" name="add_user">Add User</button>
    </form>

    <h2>Borrow Book</h2>
    <form method="POST">
        <select name="book_id">
            <?php foreach ($books as $book): ?>
                <option value="<?= htmlspecialchars($book['id']) ?>"><?= htmlspecialchars($book['title']) ?></option>
            <?php endforeach; ?>
        </select>
        <select name="user_id">
            <?php foreach ($users as $user): ?>
                <option value="<?= htmlspecialchars($user['id']) ?>"><?= htmlspecialchars($user['name']) ?></option>
            <?php endforeach; ?>
        </select>
        <button type="submit" name="borrow_book">Borrow Book</button>
    </form>

    <h2>Return Book</h2>
    <form method="POST">
        <input type="number" name="loan_id" placeholder="Loan ID" required>
        <button type="submit" name="return_book">Return Book</button>
    </form>

    <h2>List of Users with Borrowed Books</h2>
    <?php if (count($loans) > 0): ?>
        <ul>
            <?php foreach ($loans as $loan): ?>
                <li>
                    <strong>Id <?= htmlspecialchars($loan['id']) ?> - <?= htmlspecialchars($loan['name']) ?> (<?= htmlspecialchars($loan['email']) ?>)</strong> has borrowed "<em><?= htmlspecialchars($loan['title']) ?></em>" on <?= htmlspecialchars($loan['loan_date']) ?>.
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>No books are currently borrowed.</p>
    <?php endif; ?>
</body>
</html>
