<?php

namespace App\Vendor\Controller\Wallet;


use App\Controllers\BaseController;
use App\Vendor\Service\Wallet\PaymentService;


class PaymentController extends BaseController
{

    private PaymentService $paymentService;


    public function __construct()
    {
        $this->paymentService =
            new PaymentService();
    }

    public function openPaymentPage()
    {
        return view('PaymentPage');
    }



    public function createOrder()
    {

        $data =$this->request->getJSON(true);
        $amount = $data['amount'];
        $vendorId=$data['vendor_id'];
        $gateway=$data['gateway'];
        $type=$data['type'];


        // create local payment first
        $paymentId = $this->paymentService->createPayment($vendorId,$amount,$gateway, $type);
      


        // create razorpay order
        $order =$this->paymentService->createOrder($amount);

        // save razorpay order id
        $updateOrderId=$this->paymentService->updateOrderId($paymentId,$order['id']);

        return $this->response
        ->setJSON([

            "local_payment_id"=>$paymentId,
            "updateOrderId"=>$updateOrderId,

            "order_id"=>$order['id'],

            "amount"=>$amount,

            "key"=>env(
                "RAZORPAY_KEY_ID"
            ),
            "msg"=>"Order Created !!"


        ]);

    }

    public function verifyPayment()
    {
    

        $data =$this->request->getJSON(true);

        try{

            $this->paymentService->verifyPayment($data);

            return $this->response
            ->setJSON([
                "status"=>"success",
                "message"=>"Payment successful"
            ]);

        }catch(\Exception $e)
        {


            return $this->response
            ->setStatusCode(400)
            ->setJSON([

                "status"=>false,

                "message"=>$e->getMessage()

            ]);

        }

    }

}