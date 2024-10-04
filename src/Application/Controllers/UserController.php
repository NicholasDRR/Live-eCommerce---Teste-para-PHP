<?php

namespace Application\Controllers;

use Domain\Repositories\Database;
use Domain\Models\User;

/**
 * Class UserController
 *
 * Handles operations related to users, including adding users,
 * retrieving user information, and checking borrowing limits.
 */
class UserController
{
    private $db;

    /**
     * UserController constructor.
     *
     * @param Database $db The database repository for user operations.
     */
    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    /**
     * Adds a new user to the database.
     *
     * @param string $name The name of the user.
     * @param string $email The email of the user.
     * @param string $role The role of the user.
     */
    public function addUser($name, $email, $role)
    {
        $user = new User($name, $email, $role);
        $this->db->addUser($user->getName(), $user->getEmail(), $user->getRole());
    }

    /**
     * Retrieves all users from the database.
     *
     * @return array An array of users.
     */
    public function getUsers()
    {
        return $this->db->getUsers();
    }

    /**
     * Retrieves the count of books borrowed by a specific user.
     *
     * @param int $user_id The ID of the user to check.
     * @return int The number of books borrowed by the user.
     */
    public function getBorrowedCount($user_id)
    {
        return $this->db->getBorrowedCount($user_id);
    }

    /**
     * Retrieves the book borrowing limit for a specific user.
     *
     * @param int $user_id The ID of the user to check.
     * @return int The borrowing limit for the user.
     */
    public function getBookLimit($user_id)
    {
        return $this->db->getBookLimit($user_id);
    }
}
