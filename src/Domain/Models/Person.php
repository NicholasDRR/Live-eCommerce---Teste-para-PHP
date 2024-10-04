<?php

namespace Domain\Models;

/**
 * Class Person
 *
 * Represents a person with their details, including name and email.
 */
class Person
{
    protected $id; 
    protected $name;
    protected $email;

    /**
     * Person constructor.
     *
     * @param string $name The name of the person.
     * @param string $email The email of the person.
     */
    public function __construct($name, $email)
    {
        $this->name = $name;
        $this->email = $email;
    }

    /**
     * Gets the name of the person.
     *
     * @return string The name of the person.
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Gets the email of the person.
     *
     * @return string The email of the person.
     */
    public function getEmail()
    {
        return $this->email;
    }
}
