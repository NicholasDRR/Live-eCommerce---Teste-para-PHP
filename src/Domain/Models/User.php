<?php

namespace Domain\Models;

/**
 * Class User
 *
 * Represents a user in the system, extending the Person class,
 * with a role and a limit on the number of books they can borrow.
 */
class User extends Person
{
    private $role; 
    private $books_limit;

    /**
     * User constructor.
     *
     * @param string $name The name of the user.
     * @param string $email The email of the user.
     * @param string $role The role of the user (e.g., Student, Teacher).
     */
    public function __construct($name, $email, $role)
    {
        parent::__construct($name, $email);
        $this->role = $role;
        $this->setBooksLimit();
    }

    /**
     * Gets the role of the user.
     *
     * @return string The role of the user.
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Gets the limit of books the user can borrow.
     *
     * @return int The maximum number of books the user can borrow.
     */
    public function getBooksLimit()
    {
        return $this->books_limit;
    }

    /**
     * Sets the limit of books based on the user's role.
     */
    private function setBooksLimit()
    {
        if ($this->role == 'Student') {
            $this->books_limit = 1;
        } elseif ($this->role == 'Teacher') {
            $this->books_limit = 2; 
        } else {
            $this->books_limit = 0; 
        }
    }
}
