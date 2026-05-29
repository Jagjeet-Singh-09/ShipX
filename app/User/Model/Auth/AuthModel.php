<?php

namespace App\User\Model\Auth;

use Config\Database;
use CodeIgniter\Database\BaseConnection;

class AuthModel
{
    /**
     * Database connection instance.
     *
     * @var BaseConnection
     */
    protected BaseConnection $db;

    /**
     * Constructor.
     * Initializes database connection.
     */
    public function __construct()
    {
        $this->db = Database::connect();
    }

    /**
     * Create a new user and return inserted user data.
     *
     * @param string $phoneNumber User phone number
     * @param string $email       User email address
     * @param string $password    User hashed password
     * @param string $firstName   User first name
     * @param string $lastName    User last name
     *
     * @return array
     */
    public function createUser(
        string $phoneNumber,
        string $email,
        string $password,
        string $firstName,
        string $lastName
    ): array {
        $sql = "INSERT INTO users 
                (
                    phone,
                    email,
                    password,
                    first_name,
                    last_name,
                    status
                )
                VALUES (?, ?, ?, ?, ?, ?)";

        $this->db->query($sql, [
            $phoneNumber,
            $email,
            $password,
            $firstName,
            $lastName,
            1
        ]);

        $userId = $this->db->insertID();

        // Fetch inserted user data
        $sql2 = "SELECT * FROM users WHERE id = ?";

        $query = $this->db->query($sql2, [$userId]);

        return $query->getRowArray();
    }

    /**
     * Fetch user data using email address.
     *
     * @param string $email User email address
     *
     * @return array
     */
    public function getDataByMail(string $email): array
    {
        $sql = "SELECT * FROM users WHERE email = ?";

        $query = $this->db->query($sql, [$email]);

        return $query->getRowArray();
    }
}