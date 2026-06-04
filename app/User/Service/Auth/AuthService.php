<?php

namespace App\User\Service\Auth;

use App\User\Model\Auth\UserModel;
use App\User\Model\Auth\AuthModel;
use App\Helpers\ApiResponseHelper;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * Service for authentication-related business logic.
 */
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
     * API response helper instance.
     *
     * @var ApiResponseHelper
     */
    protected ApiResponseHelper $ApiResponseHelper;

    /**
     * Constructor.
     * Initializes model objects.
     */
    public function __construct()
    {
        $this->AuthModel = new AuthModel();
        $this->userModel = new UserModel();
        $this->ApiResponseHelper = new ApiResponseHelper();
    }

    /**
     * Create a new user and initialize session.
     *
     * Also sets hierarchy for the created user.
     *
     * @param array $data User data
     * @return array|null
     */
    public function createUser(array $data): ?array {

        $phoneNumber = $data['phone'];
        $email = $data['email'];
        $password = $data['password'];
        $firstName = $data['first_name'];
        $lastName = $data['last_name'];
        $path = $data['path'] ?? null;
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $user = $this->AuthModel->createUser($phoneNumber, $email, $hashedPassword, $firstName, $lastName);

        // Set session
        $session = session();

        $session->set([
            'email'      => $user['email'],
            'first_name' => $user['first_name'],
            'last_name'  => $user['last_name'],
            'id'         => $user['id'],
        ]);

        $this->userModel->setHierarchy($user['id'], $path);

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
     * @return ResponseInterface
     */
    public function checkLogIn(
        string $email,
        string $password
    ): ResponseInterface {
        // Fetch user by email
        $user = $this->AuthModel->getDataByMail($email);

        // Check if user exists
        if (!$user) {

            return $this->ApiResponseHelper->apiResponseHandler("User not found", null, 404);
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

            return $this->ApiResponseHelper->apiResponseHandler("Login successful", $user, 200);
        }

        // Invalid password
        return $this->ApiResponseHelper->apiResponseHandler("Invalid password", null, 401);
    }
}
