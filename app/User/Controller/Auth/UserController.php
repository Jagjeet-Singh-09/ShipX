<?php

namespace App\User\Controller\Auth;

use App\User\Service\Auth\UserService;
use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Validations\UserValidations;

class UserController extends BaseController
{
    /**
     * User service instance.
     *
     * @var UserService
     */
    protected UserService $userService;
    protected UserValidations $userValidations;


    /**
     * Constructor.
     * Initializes the UserService object.
     */
    public function __construct()
    {
        $this->userService = new UserService();
         $this->userValidations = new UserValidations();
    }
    /**
     * Create a new user.
     *
     * Reads JSON request data and passes it to the service layer
     * for user creation.
     *
     * @return ResponseInterface
     */
    public function addUser(): ResponseInterface
    {
        $data = $this->request->getJSON(true);

        $email = $data['email'];
        

        if (!$this->userValidations->checkEmail($email)) {
            return $this->response
                ->setStatusCode(400)
                ->setJSON([
                    'status' => 'error',
                    'message' => 'Please add Valid Email'
                ]);
        }

        
        $password = $data['password'];

        if (!$this->userValidations->checkPassword($password)) {
            return $this->response
                ->setStatusCode(400)
                ->setJSON([
                    'status' => 'error',
                    'message' => 'Please add Valid Password'
                ]);
        }

        $phoneNumber = $data['phone'];
        if (!$this->userValidations->checkMobileNumber($phoneNumber)) {
            return $this->response
                ->setStatusCode(400)
                ->setJSON([
                    'status' => 'error',
                    'message' => 'Please add Valid Phone Number'
                ]);
        }


        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $firstname = $data['first_name'];

        if (!$this->userValidations->checkFirstName($firstname)) {
            return $this->response
                ->setStatusCode(400)
                ->setJSON([
                    'status' => 'error',
                    'message' => 'Please add Valid First Name'
                ]);
        }
        $lastname = $data['last_name'];

        if (!$this->userValidations->checkLastName($lastname)) {
            return $this->response
                ->setStatusCode(400)
                ->setJSON([
                    'status' => 'error',
                    'message' => 'Please add Valid Last Name'
                ]);
        }

        return $this->userService->addUser($data);
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