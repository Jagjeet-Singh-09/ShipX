<?php

namespace App\User\Controller\Auth;

use App\Controllers\BaseController;
use App\Helpers\ApiResponseHelper;
use App\User\Service\Auth\AuthService;
use App\Validations\UserValidations;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * Controller for user authentication operations.
 */
class AuthController extends BaseController
{
    /**
     * Authentication service for user login and registration.
     *
     * @var AuthService
     */
    protected AuthService $authService;

    /**
     * Validation helper for user input.
     *
     * @var UserValidations
     */
    protected UserValidations $userValidations;

    /**
     * API response helper for standardized JSON responses.
     *
     * @var ApiResponseHelper
     */
    protected ApiResponseHelper $apiResponse;

    public function __construct()
    {
        $this->authService = new AuthService();
        $this->userValidations = new UserValidations();
        $this->apiResponse = new ApiResponseHelper();
    }

    /**
     * Register a new user from request JSON payload.
     *
     * @return ResponseInterface
     */
    public function createUser(): ResponseInterface
    {
        $data = $this->request->getJSON(true);

        $firstName = $data['first_name'];
        if (!$this->userValidations->checkFirstName($firstName)) {
            return $this->apiResponse->apiResponseHandler('Please add a valid first name', null, 400);
        }

        $lastName = $data['last_name'];
        if (!$this->userValidations->checkLastName($lastName)) {
            return $this->apiResponse->apiResponseHandler('Please add a valid last name', null, 400);
        }

        $deptId = $data['dept_id'];
        if (!$this->userValidations->checkId($deptId)) {
            return $this->apiResponse->apiResponseHandler('Please add a valid department ID', null, 400);
        }

        $managerId = $data['path'];
        if (!$this->userValidations->checkId($managerId)) {
            return $this->apiResponse->apiResponseHandler('Please add a valid manager ID', null, 400);
        }

        $email = $data['email'];
        if (!$this->userValidations->checkEmail($email)) {
            return $this->apiResponse->apiResponseHandler('Please add a valid email', null, 400);
        }

        $password = $data['password'];
        if (!$this->userValidations->checkPassword($password)) {
            return $this->apiResponse->apiResponseHandler('Please add a valid password', null, 400);
        }

        $phone = $data['phone'];
        if (!$this->userValidations->checkMobileNumber($phone)) {
            return $this->apiResponse->apiResponseHandler('Please add a valid phone number', null, 400);
        }

        $groupId = $data['group_id'];
        if (!$this->userValidations->checkId($groupId)) {
            return $this->apiResponse->apiResponseHandler('Please add a valid group ID', null, 400);
        }

        $result = $this->authService->createUser($data);

        if ($result) {
            return $this->apiResponse->apiResponseHandler('User created successfully', $result, 201);
        }

        return $this->apiResponse->apiResponseHandler('Registration failed', null, 400);
    }

    /**
     * Authenticate a user with email and password.
     *
     * @return ResponseInterface
     */
    public function checkLogIn(): ResponseInterface
    {
        $data = $this->request->getJSON(true);

        $email = $data['email'];
        if (!$this->userValidations->checkEmail($email)) {
            return $this->apiResponse->apiResponseHandler('Please add a valid email', null, 400);
        }

        $password = $data['password'];
        if (!$this->userValidations->checkPassword($password)) {
            return $this->apiResponse->apiResponseHandler('Please add a valid password', null, 400);
        }

        $result = $this->authService->checkLogIn($email, $password);
        $status = $result->getStatusCode();

        if ($status === 401 || $status === 404) {
            return $this->apiResponse->apiResponseHandler('Invalid email or password', null, 401);
        }

        return $this->apiResponse->apiResponseHandler('Login successful', $result, 200);
    }

    /**
     * Destroy the current session and log out the user.
     *
     * @return ResponseInterface
     */
    public function logOut(): ResponseInterface
    {
        $session = session();
        $session->destroy();

        return $this->apiResponse->apiResponseHandler('Logout successful', null, 200);
    }
}
