<?php

namespace App\Vendor\Service\Wallet;

use Razorpay\Api\Api;
use App\Vendor\Model\Wallet\PaymentModel;

class PaymentService
{
    private Api $api;
    private PaymentModel $paymentModel;
    private WalletService $walletService;


    public function __construct()
    {
        $this->api = new Api(
            env('RAZORPAY_KEY_ID'),
            env('RAZORPAY_KEY_SECRET')
        );

        $this->paymentModel = new PaymentModel();

        $this->walletService = new WalletService();
    }


    public function createOrder(float $amount)
    {
        return $this->api->order->create(['amount' => $amount,'currency' => 'INR']);
    }


    public function verifySignature(array $attributes)
    {
        return $this->api->utility->verifyPaymentSignature($attributes);
    }

    public function createPayment($vendorId,$amount,$gateway, $type)
    {
        return $this->paymentModel->createPayment($vendorId,$amount,$gateway, $type);
    }


    public function updateOrderId($paymentId,$orderId)
    {
        return $this->paymentModel->updateOrderId($paymentId,$orderId);
    }

    



    public function verifyPayment(array $data): bool
    {

        $db = db_connect();

        $db->transBegin();

        try {
            // 1. Verify Razorpay signature
            $this->verifySignature(['razorpay_order_id'=> $data['razorpay_order_id'],
            'razorpay_payment_id'=> $data['razorpay_payment_id'],'razorpay_signature'=> $data['razorpay_signature']
            ]);

            // 2. Find local payment
            $payment =$this->paymentModel->getPaymentById($data['local_payment_id']);

            if(!$payment)
            {
                throw new \Exception(
                    "Payment not found"
                );
            }

            // 3. Prevent duplicate wallet credit

            if($payment['status']=="success")
            {
                throw new \Exception(
                    "Payment already processed"
                );
            }

            // 4. Check order id

            if(
                $payment['gateway_order_id']
                !=
                $data['razorpay_order_id']
            )
            {
                throw new \Exception(
                    "Order mismatch"
                );
            }
            // 5. Update payment table
            $this->paymentModel
            ->markSuccess(
                $payment['payment_id'],
                $data['razorpay_payment_id']
            );

            // 6. Credit wallet

            $this->walletService
            ->credit(
                $payment['vendor_id'],
                $payment['amount']
            );

            $db->transCommit();
            return true;


        }catch(\Exception $e)
        {

            $db->transRollback();

            throw $e;
        }

    }

}