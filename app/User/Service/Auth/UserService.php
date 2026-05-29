<?php

namespace App\User\Service\Auth;

use App\User\Model\Auth\UserModel;
use App\Helpers\ApiResponseHelper;
use CodeIgniter\HTTP\ResponseInterface;

class UserService
{
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
    protected ApiResponseHelper $apiResponseHelper;

    /**
     * Constructor.
     * Initializes required model and helper classes.
     */
    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->apiResponseHelper = new ApiResponseHelper();
    }

    /**
     * Add a new user and set hierarchy.
     *
     * @param array $data User data
     *
     * @return ResponseInterface
     */
    public function addUser(array $data): ResponseInterface
    {
        // Insert user
        $id = $this->userModel->addUser($data);

        if (!$id) {

            return $this->apiResponseHelper->apiResponseHandler(
                "User Addition Failed",
                null,
                400
            );
        }

        // Set user hierarchy
        $setHierarchy = $this->userModel->setHierarchy(
            $id,
            $data['path']
        );

        if (!$setHierarchy) {

            return $this->apiResponseHelper->apiResponseHandler(
                "Hierarchy Setting Failed",
                null,
                400
            );
        }

        return $this->apiResponseHelper->apiResponseHandler(
            "User Addition Successful",
            null,
            200
        );
    }

    /**
     * Create a new user group.
     *
     * @param array $data Group data
     *
     * @return ResponseInterface
     */
    public function createUserGroup(array $data): ResponseInterface
    {
        $result = $this->userModel->createUserGroup($data);

        if (!$result) {

            return $this->apiResponseHelper->apiResponseHandler(
                "Group Creation Failed",
                null,
                400
            );
        }

        return $this->apiResponseHelper->apiResponseHandler(
            "Group Creation Successful",
            null,
            200
        );
    }

    /**
     * Fetch all users.
     *
     * @return ResponseInterface
     */
    public function getAllUser(): ResponseInterface
    {
        $data = $this->userModel->getAllUser();

        if (!$data) {

            return $this->apiResponseHelper->apiResponseHandler(
                "Failed to fetch users",
                null,
                400
            );
        }

        return $this->apiResponseHelper->apiResponseHandler(
            "Data fetched",
            $data,
            200
        );
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
        $data = $this->userModel->getSpecificUser($id);

        if (!$data) {

            return $this->apiResponseHelper->apiResponseHandler(
                "Failed to fetch User",
                null,
                400
            );
        }

        return $this->apiResponseHelper->apiResponseHandler(
            "User fetched successfully",
            $data,
            200
        );
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
        $data = $this->userModel->getGroupMembers($id);

        if (!$data) {

            return $this->apiResponseHelper->apiResponseHandler(
                "Failed to fetch Group Members",
                null,
                400
            );
        }

        return $this->apiResponseHelper->apiResponseHandler(
            "Group members fetched successfully",
            $data,
            200
        );
    }

    /**
     * Fetch hierarchy details of a user.
     *
     * @param int $id User ID
     *
     * @return ResponseInterface
     */
    public function getHierarchy(int $id): ResponseInterface
    {
        $result = $this->userModel->getHierarchy($id);

        if (!$result) {

            return $this->apiResponseHelper->apiResponseHandler(
                "Failed to fetch Hierarchy",
                null,
                400
            );
        }

        return $this->apiResponseHelper->apiResponseHandler(
            "Hierarchy fetched successfully",
            $result,
            200
        );
    }
}