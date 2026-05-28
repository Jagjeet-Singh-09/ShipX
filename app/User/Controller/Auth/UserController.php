<?php

namespace App\User\Controller\Auth;

use App\User\Service\Auth\UserService;
use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class UserController extends BaseController
{
    protected UserService $userService;

    public function __construct()
    {
        $this->userService = new UserService();
    }

    public function addUser() : ResponseInterface
    {
        $data = $this->request->getJSON(true);
        $result = $this->userService->addUser($data);
        return $this->response->setJSON($result);
    }

    public function createUserGroup(): ResponseInterface
    {
        $data = $this->request->getJSON(true);
        $result = $this->userService->createUserGroup($data);
        return $this->response->setJSON($result);
    }

    public function getAllUser(): ResponseInterface
    {
        $result = $this->userService->getAllUser();

        return $this->response->setJSON($result);
    }

    public function getSpecificUser($id): ResponseInterface
    {
        $result = $this->userService->getSpecificUser($id);

        return $this->response->setJSON($result);
    }

    public function getGroupMembers($id): ResponseInterface
    {
        
        $result = $this->userService->getGroupMembers($id);
        return $this->response->setJSON($result);

    }
}