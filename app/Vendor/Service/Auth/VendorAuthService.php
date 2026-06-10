<?php

namespace App\Vendor\Service\Auth;

use App\Vendor\Model\Auth\VendorAuthModel;
use App\Admin\Controller\Groups\GroupController;
use App\Helpers\ApiResponseHelper;
use CodeIgniter\HTTP\ResponseInterface;


class VendorAuthService
{
    protected vendorAuthModel $vendorAuthModel;
    protected ApiResponseHelper $ApiResponseHelper;

    public function __construct()
    {
        $this->vendorAuthModel = new VendorAuthModel();
        $this->ApiResponseHelper = new ApiResponseHelper();
    }

    public function vendorRegister( array $data)
    {
        $user= $this->vendorAuthModel->vendorRegister($data);
        $session = session();

            $session->set([
                'email' => $user['email'],
                'first_name'  => $user['first_name'],
                'last_name'  => $user['first_name'],
                'id' => $user['id'],
            ]);

        //$this->groupController->createPreDefinedGroups();

        return $user;
    }

    public function stepOneData($data, $id){
        return $this->vendorAuthModel->stepOneData($data, $id);
    }

    public function stepTwoData($data){
        return $this->vendorAuthModel->stepTwoData($data);
    }

    public function stepThreeData($data, $id){
        $result = $this->vendorAuthModel->stepThreeData($data, $id);
        return $result;
    }
    

    public function checkLogIn(
        string $email,
        string $password
    ): ResponseInterface {
        // Fetch user by email
        $user = $this->vendorAuthModel->getDataByMail($email);

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


