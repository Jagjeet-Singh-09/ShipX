<?php 
namespace App\User\Service\Auth;
use App\User\Model\Auth\UserModel;
use App\Helpers\ApiResponseHelper;
use CodeIgniter\HTTP\ResponseInterface;

class UserService{
    protected UserModel $userModel;
    protected ApiResponseHelper $apiResponseHelper;

    public function __construct()
    {
        $this->userModel=new UserModel();
        $this->apiResponseHelper=new ApiResponseHelper();

    }

    public function addUser($data): ResponseInterface{
        $result = $this->userModel->addUser($data);
        if(!$result){

            return $this->apiResponseHelper->apiResponseHandler("User Addition Failed",null,400);

        }

        return $this->apiResponseHelper->apiResponseHandler("User Addition Successful",null,200);

    }

    public function createUserGroup($data): ResponseInterface{
        $result = $this->userModel->createUserGroup($data);
        if(!$result){
            return $this->apiResponseHelper->apiResponseHandler("Group Creation Failed",null,400);
        }

        return $this->apiResponseHelper->apiResponseHandler("Group Creation Successful",null,200);

    }

    public function getAllUser(): ResponseInterface{
        $data = $this->userModel->getAllUser();
        if(!$data){
            return $this->apiResponseHelper->apiResponseHandler("Group Creation Failed",null,400);
        }
        return $this->apiResponseHelper->apiResponseHandler("Group Creation Successful",$data,200);
    }

    public function getSpecificUser($id): ResponseInterface
    {
        $data = $this->userModel->getSpecificUser($id);
        if(!$data){
            return $this->apiResponseHelper->apiResponseHandler("Failed to fetch User",null,400);
        }
        return $this->apiResponseHelper->apiResponseHandler("",$data,200);
    }

    public function getGroupMembers($id): ResponseInterface
    {
        $data = $this->userModel->getGroupMembers($id);
        if(!$data){
            return $this->apiResponseHelper->apiResponseHandler("Failed to fetch Group Members",null,400);
        }

        return $this->apiResponseHelper->apiResponseHandler("",$data,200);
    }

}