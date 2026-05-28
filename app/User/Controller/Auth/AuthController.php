<?php

namespace App\User\Controller\Auth;

use App\Controllers\BaseController;
use App\User\Service\Auth\AuthService;
use App\Validations\UserValidations;
use CodeIgniter\HTTP\ResponseInterface;



class AuthController extends BaseController
{
    protected AuthService $authService;
    protected UserValidations $userValidations;


    public function __construct()
    {
        $this->authService = new AuthService();
        $this->userValidations = new UserValidations();
    }

    // Registering Super Admin

    public function createUser(): ResponseInterface
    {
        $data=$this->request->getJSON(true);
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
        $result = $this->authService->createUser($phoneNumber, $email, $hashedPassword, $firstname, $lastname);



        
        if ($result) {

            return $this->response->setJSON([
                "status" => "success",
                "message" => "User registered successfully"
            ]);
        }

        return $this->response->setJSON([
            "status" => "error",
            "message" => "Registration failed"
        ]);
    }


    // Login logic
     public function checkLogIn(): ResponseInterface
    {
        $data=$this->request->getJSON(true);
        $email = $data['email'];

        if (!$this->userValidations->checkEmail($email)) {
            return $this->response
                ->setStatusCode(400)
                ->setJSON([
                    'status' => 'error',
                    'message' => 'Please add valid Email'
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

        $result = $this->authService->checkLogIn($email, $password);
        if ($result['status'] == 'error') {

            return $this->response
                ->setStatusCode(401)
                ->setJSON($result);
        }

        // SUCCESS
        return $this->response
            ->setStatusCode(200)
            ->setJSON($result);
    }

}