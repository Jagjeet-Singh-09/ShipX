<?php

namespace App\Vendor\Controller\Auth;

use App\Controllers\BaseController;
use App\Vendor\Service\Auth\VendorAuthService;
use App\Validations\UserValidations;
use App\Helpers\ApiResponseHelper;
use CodeIgniter\HTTP\ResponseInterface;



class VendorAuthController extends BaseController


{
    protected $authService;
    protected $userValidations;
    protected ApiResponseHelper $apiResponse;


    public function __construct()
    {
        $this->authService = new VendorAuthService();
        $this->userValidations = new UserValidations();
        $this->apiResponse = new ApiResponseHelper();
    }
    
    public function vendorRegister()
    { 
        $data = $this->request->getJSON(true);
        
        
        $email = $data['email'];
        if (!$this->userValidations->checkEmail($email)) {
            return $this->apiResponse->apiResponseHandler(
                "please add a valid email",null,400);
        }
        $password = $data['password'];
        if (!$this->userValidations->checkPassword($password)) {
            return $this->apiResponse->apiResponseHandler("please add a valid password",null,400);
        }

        $phone = $data['phone'];
        if (!$this->userValidations->checkMobileNumber($phone)) {
            return $this->apiResponse->apiResponseHandler("please add a valid phone number",null,400);
        }   


        $result = $this->authService->vendorRegister($data);

        if ($result) {
            return $this->apiResponse->apiResponseHandler("User created successfully", $result, 201);
        }

        return $this->apiResponse->apiResponseHandler("Registration failed", null, 400);
    }

    public function stepOneData()
    {
        $data = $this->request->getJSON(true);

        $firstName = $data['first_name'];

        if (!$this->userValidations->checkFirstName($firstName)) {
            return $this->apiResponse->apiResponseHandler("please add a valid first name",null,400
            );
        }

        $lastName = $data['last_name'];

        if (!$this->userValidations->checkLastName($lastName)) {
            return $this->apiResponse->apiResponseHandler("please add a valid last name",null,400            );
        }

        $id=$data['id'];

        $result = $this->authService->stepOneData($data, $id);
        

        if (!$result) {
            return $this->apiResponse->apiResponseHandler("Step One not Saved", $result, 201);
        }

        //$this->stepUpdate($id);
        return $this->apiResponse->apiResponseHandler("Step one Saved Successfully", null, 200);
    }

    public function stepTwoData()
    {
        $data = $this->request->getJSON(true);
        $result = $this->authService->stepTwoData($data);

        if (!$result) {
            return $this->apiResponse->apiResponseHandler("Step Two not Saved", $result, 201);
        }

        return $this->apiResponse->apiResponseHandler("Step Two Saved Successfully", null, 200);
    }



    public function stepThreeData()
    {
    
        $aadharCardImage = $this->request->getFile('aadhar_upload');
        $aadharName = $aadharCardImage->getRandomName();

        $aadharCardImage->move(FCPATH . 'uploads', $aadharName);

        $aadharUrl = 'uploads/' . $aadharName;

        $panCardImage = $this->request->getFile('pan_upload');
        $panName = $panCardImage->getRandomName();

        $panCardImage->move(FCPATH . 'uploads', $panName);
        $panUrl = 'uploads/' . $panName;

        $gstfile=$this->request->getFile('gst_upload');
        $gstName=$gstfile->getRandomName();
        $gstfile->move(FCPATH . 'uploads', $gstName);
        $gstUrl = 'uploads/' . $gstName;


        $aadharCardNumber =$this->request->getPost('aadhar_no');
        if (!$this->userValidations->checkAadharNumber($aadharCardNumber)) {
            return $this->apiResponse->apiResponseHandler( "please add Valid Aadhar Card Number",null,400);
        }

        $panCardNumber  = $this->request->getPost('pan_no');
        if (!$this->userValidations->checkPanCard($panCardNumber)) {
            return $this->apiResponse->apiResponseHandler( "please add Valid Pan Card Number",null,400);
        }

        $gstNumber = $this->request->getPost('gst_no');
        if (!$this->userValidations->checkGSTNumber($gstNumber)) {
            return $this->apiResponse->apiResponseHandler( "please add Valid GST number",null,400);
        }

        $gstNumber = $this->request->getPost('gst_no');
        if (!$this->userValidations->checkGSTNumber($gstNumber)) {
            return $this->apiResponse->apiResponseHandler( "please add Valid GST number",null,400);
        }

        $data = [
            'aadharCardNumber' => $aadharCardNumber,
            'panCardNumber' => $panCardNumber,
            'gstNumber' => $gstNumber,
            'panCardImage' => $panUrl,
            'aadharCardImage' => $aadharUrl,
            'gst'=>$gstUrl,
        ];
        $id= $this->request->getPost('vendor_id');


        $result =  $this->authService->stepThreeData($data, $id);
        if (!$result) {
            return $this->apiResponse->apiResponseHandler("Step Three not Saved", $result, 201);
        }

        return $this->apiResponse->apiResponseHandler("Step Three Saved Successfully", null, 200);
    }

    public function checkLogIn(): ResponseInterface
    {
        $data = $this->request->getJSON(true);
        $email = $data['email'];
        $password = $data['password'];

        $email = $data['email'];
        if (!$this->userValidations->checkEmail($email)) {
            return $this->apiResponse->apiResponseHandler(
                "please add a valid email",null,400);
        }
        $password = $data['password'];
        if (!$this->userValidations->checkPassword($password)) {
            return $this->apiResponse->apiResponseHandler("please add a valid password",null,400);
        }

        $result = $this->authService->checkLogIn($email, $password);
        $status = $result->getStatusCode();
        if ($status == 401 || $status == 404) {
            return $this->apiResponse->apiResponseHandler("Invalid email or password", null, 401);
        }       
        return $this->apiResponse->apiResponseHandler("Login successful", $result, 200);
    }
}
//     public function stepUpdate($id)
//     {
//         $result = $this->profileService->updateStep($id);

//         if ($result) {
//             return $this->response->setJSON([
//                 'status' => 'success',
//                 'message' => 'Step updated successfully'
//             ]);
//         }

//         return $this->response->setJSON([
//             'status' => 'error',
//             'message' => 'Failed to update step'
//         ]);
// }


