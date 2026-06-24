<?php

namespace App\User\Controller\Auth;

use App\User\Service\Auth\UserService;
use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Validations\UserValidations;

/**
 * Controller for user management endpoints.
 */
class UserController extends BaseController
{
    /**
     * User service instance.
     *
     * @var UserService
     */
    protected UserService $userService;

    /**
     * Validation helper for user requests.
     *
     * @var UserValidations
     */
    protected UserValidations $userValidations;

    /**
     * Constructor.
     * Initializes the UserService and validation helper.
     */
    public function __construct()
    {
        $this->userService = new UserService();
        $this->userValidations = new UserValidations();
    }

    /**
     * Create a new user group.
     *
     * Reads JSON request data and sends it to the service layer
     * for group creation.
     *
     * @return ResponseInterface
     */
    public function createUserGroup(): ResponseInterface
    {
        $data = $this->request->getJSON(true);

        return $this->userService->createUserGroup($data);
    }

    /**
     * Fetch all users.
     *
     * @return ResponseInterface
     */
    public function getAllUser(): ResponseInterface
    {
        return $this->userService->getAllUser();
    }

    /**
     * Fetch hierarchy details of a specific user.
     *
     * @param int $id User ID
     *
     * @return ResponseInterface
     */
    public function getHierarchy(int $id): ResponseInterface
    {
        return $this->userService->getHierarchy($id);
    }

    /**
     * Fetch details of a specific user.
     *
     * @param int $id User ID
     *
     * @return ResponseInterface
     */
    public function getSpecificUser(int $id): ResponseInterface
    {
        return $this->userService->getSpecificUser($id);
    }

    /**
     * Fetch all members of a specific group.
     *
     * @param int $id Group ID
     *
     * @return ResponseInterface
     */
    public function getGroupMembers(int $id): ResponseInterface
    {
        $result = $this->userService->getGroupMembers($id);

        return $this->response->setJSON($result);
    }
    

}