<?php

namespace App\User\Model\Auth;

use Config\Database;

class AuthModel
{
    protected $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function createUser($phoneNumber, $email, $password, $firstName, $lastName):array
    {
        $sql = "INSERT INTO users 
                (phone, email, password, first_name, last_name, status)
                VALUES (?, ?, ?, ?, ?, ?)";

        $query=$this->db->query($sql, [
            $phoneNumber,
            $email,
            $password,
            $firstName,
            $lastName,
            1
        ]);

        $userId = $this->db->insertID();

        // FETCH USER DATA
        $sql3 = "SELECT * FROM users WHERE id = ?";

        $query = $this->db->query($sql3, [$userId]);

        return $query->getRowArray();
    }

    public function getDataByMail($email):array
    {
        $sql = "SELECT * FROM users WHERE email = ?";

        $query = $this->db->query($sql, [$email]);

        return $query->getRowArray();
        
    }
}
