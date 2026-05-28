<?php

namespace App\User\Service\Auth;
use App\User\Model\Auth\AuthModel;


class AuthService
{
    protected AuthModel $AuthModel;

    public function __construct()
    {
        $this->AuthModel = new AuthModel();
    }

    public function createUser($phoneNumber, $email, $password, $firstName , $lastName) : array
    {
        $user= $this->AuthModel->createUser($phoneNumber, $email, $password, $firstName , $lastName);

        // Setting Session
        $session = session();

            $session->set([
                'email' => $user['email'],
                'first_name'  => $user['first_name'],
                'last_name'  => $user['first_name'],
                'id' => $user['id'],
            ]);

        return $user;
    }

    public function checkLogIn($email, $password) : array
    {
        $user = $this->AuthModel->getDataByMail($email);
        
        if (!$user) {

            return [
                "status" => "error",
                "message" => "Email not found"
            ];
        }

        if (password_verify($password,$user['password'])) {

            // CREATE SESSION
            $session = session();

            $session->set([
                'email' => $user['email'],
                'first_name'  => $user['first_name'],
                'last_name'  => $user['first_name'],
                'id' => $user['id'],
            ]);

            return [
                "status" => "success",
                "message" => "Login successful",
                "user" => $user
            ];
        }

        // WRONG PASSWORD
        return [
            "status" => "error",
            "code" => 401,
            "message" => "Invalid password"
        ];
    }
}