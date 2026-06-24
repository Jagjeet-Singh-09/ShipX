<?php

namespace App\Validations;

use CodeIgniter\HTTP\ResponseInterface;
use App\Helpers\ApiResponseHelper;

class UserValidations
{
    protected ApiResponseHelper $apiResponse;

    public function __construct()
    {
        $this->apiResponse = new ApiResponseHelper();
    }

    // CHECK MOBILE NUMBER
    public function checkMobileNumber($mobile)
    {
        // ONLY 10 DIGITS
        if (preg_match('/^[6-9]\d{9}$/', $mobile)) {
            return true;
        }

        return false;
    }

    public function checkPassword($password)
    {
        /*
        CONDITIONS:
        - Minimum 8 characters
        - At least 1 uppercase
        - At least 1 lowercase
        - At least 1 number
        - At least 1 special character
    */

        if (
            preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $password)
        ) {
            return true;
        }

        return false;
    }

    public function checkEmail($email)
    {
        // EMAIL REGEX

        if (preg_match('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', $email)) {
            return true;
        }

        return false;
    }


    // CHECK AADHAR NUMBER
    public function checkAadharNumber($aadhar)
    {
        // ONLY 12 DIGITS
        if (preg_match('/^[0-9]{12}$/', $aadhar)) {
            return true;
        }

        return false;
    }


    // CHECK PAN CARD
    public function checkPanCard($pan)
    {
        // FORMAT : ABCDE1234F
        if (preg_match('/^[A-Z]{5}[0-9]{4}[A-Z]{1}$/', $pan)) {
            return true;
        }

        return false;
    }


    // CHECK GST NUMBER
    public function checkGSTNumber($gst)
    {
        // GST FORMAT
        if (preg_match('/^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[A-Z0-9]{3}$/', $gst)) {
            return true;
        }

        return false;
    }




    // CHECK FIRST NAME
    public function checkFirstName($firstName)
    {
        // ONLY LETTERS
        if (preg_match('/^[a-zA-Z ]+$/', $firstName)) {
            return true;
        }

        return false;
    }


    // CHECK LAST NAME
    public function checkLastName($lastName)
    {
        // ONLY LETTERS
        if (preg_match('/^[a-zA-Z ]+$/', $lastName)) {
            return true;
        }

        return false;
    }

    // CHECK DEPARTMENT ID
    public function checkId($deptId)
    {
        // ONLY NUMBERS
        if (preg_match('/^[0-9]+$/', $deptId)) {
            return true;
        }
        return false;
    }

    // public function dataValidator($data): ?ResponseInterface
    // {



    //     if (isset($data['lastname']) && !$this->checkLastName($data['lastname'])) {
    //         return $this->apiResponse->apiResponseHandler(
    //             "please add a valid last name",
    //             null,
    //             400
    //         );
    //     }

    //     if (isset($data['firstname']) && !$this->checkFirstName($data['firstname'])) {
    //         return $this->apiResponse->apiResponseHandler(
    //             "please add a valid first name",
    //             null,
    //             400
    //         );
    //     }

    //     if (isset($data['phone']) && !$this->checkMobileNumber($data['phone'])) {
    //         return $this->apiResponse->apiResponseHandler(
    //             "please add a valid phone number",
    //             null,
    //             400
    //         );
    //     }

    //     if (isset($data['email']) && !$this->checkEmail($data['email'])) {
    //         return $this->apiResponse->apiResponseHandler(
    //             "please add a valid email",
    //             null,
    //             400
    //         );
    //     }

    //     if (isset($data['password']) && !$this->checkPassword($data['password'])) {
    //         return $this->apiResponse->apiResponseHandler(
    //             "please add a valid password",
    //             null,
    //             400
    //         );
    //     }

    //     if (isset($data['dept_id']) && !$this->checkId($data['dept_id'])) {
    //         return $this->apiResponse->apiResponseHandler(
    //             "please add a valid department ID",
    //             null,
    //             400
    //         );
    //     }

    //     if (isset($data['manager_id']) && !$this->checkId($data['manager_id'])) {
    //         return $this->apiResponse->apiResponseHandler(
    //             "please add a valid manager ID",
    //             null,
    //             400
    //         );
    //     }

    //     if (isset($data['group_id']) && !$this->checkId($data['group_id'])) {
    //         return $this->apiResponse->apiResponseHandler(
    //             "please add a valid group ID",
    //             null,
    //             400
    //         );
    //     }

    //     if (isset($data['path']) && !$this->checkId($data['path'])) {
    //         return $this->apiResponse->apiResponseHandler(
    //             "please add a valid path",
    //             null,
    //             400
    //         );
    //     }
        
    //     return null;
    // }
}
