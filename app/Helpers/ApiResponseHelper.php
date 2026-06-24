<?php

namespace App\Helpers;

use CodeIgniter\HTTP\ResponseInterface;
use App\Validations\UserValidations;


class ApiResponseHelper
{
    public static function apiResponseHandler($message, $data, $statusCode): ResponseInterface
    {

        return response()->setStatusCode($statusCode)
            ->setJSON([
                "message" => $message,
                "data" => $data,
                "statusCode" => $statusCode
            ]);
    }

    
}