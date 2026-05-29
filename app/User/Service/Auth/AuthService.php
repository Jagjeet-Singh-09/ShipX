<?php

namespace App\User\Service\Auth;

use App\User\Model\Auth\UserModel;
use App\User\Model\Auth\AuthModel;

class AuthService
{
    /**
     * Auth model instance.
     *
     * @var AuthModel
     */
    protected AuthModel $AuthModel;

    /**
     * User model instance.
     *
     * @var UserModel
     */
    protected UserModel $userModel;

    /**
     * Constructor.
     * Initializes model objects.
     */
    public function __construct()
    {
        $this->AuthModel = new AuthModel();
        $this->userModel = new UserModel();
    }

    /**
     * Create a new user and initialize session.
     *
     * Also sets hierarchy for the created user.
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
        // Create user
        $user = $this->AuthModel->createUser(
            $phoneNumber,
            $email,
            $password,
            $firstName,
            $lastName
        );

        // Set session
        $session = session();

        $session->set([
            'email'      => $user['email'],
            'first_name' => $user['first_name'],
            'last_name'  => $user['last_name'],
            'id'         => $user['id'],
        ]);

        // Set hierarchy for user
        $this->userModel->setHierarchy($user['id'], null);

        return $user;
    }

    /**
     * Validate user login credentials.
     *
     * Creates session if credentials are valid.
     *
     * @param string $email    User email address
     * @param string $password User password
     *
     * @return array
     */
    public function checkLogIn(
        string $email,
        string $password
    ): array {
        // Fetch user by email
        $user = $this->AuthModel->getDataByMail($email);

        // Check if user exists
        if (!$user) {

            return [
                "status"  => "error",
                "message" => "Email not found"
            ];
        }

        // Verify password
        if (password_verify($password, $user['password'])) {

            // Create session
            $session = session();

            $session->set([
                'email'      => $user['email'],
                'first_name' => $user['first_name'],
                'last_name'  => $user['last_name'],
                'id'         => $user['id'],
            ]);

            return [
                "status"  => "success",
                "message" => "Login successful",
                "user"    => $user
            ];
        }

        // Invalid password
        return [
            "status"  => "error",
            "code"    => 401,
            "message" => "Invalid password"
        ];
    }
}