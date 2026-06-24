<?php

namespace App\Vendor\Model\Wallet;

use CodeIgniter\Model;

class PaymentModel extends Model
{
    public function createPayment(int $vendorId,float $amount,string $gateway, string $type)
    {
        $sql = "INSERT INTO payments(vendor_id,amount,gateway,type) VALUES(?, ?, ?, ?)";

        $this->db->query($sql,[$vendorId, $amount, $gateway, $type]);

        return $this->db->insertID();
    }

    public function updateOrderId( int $paymentId,string $orderId)
    {
        $sql = " UPDATE payments SET gateway_order_id = ? WHERE payment_id = ?";

        return $this->db->query(  $sql,  [$orderId, $paymentId] );
       
    }

    public function markSuccess( int $paymentId,string $gatewayPaymentId)
    {
        $sql = " UPDATE payments SET status = 'success', gateway_order_id = ? WHERE payment_id = ? ";

        return $this->db->query( $sql,[$gatewayPaymentId, $paymentId]);
    }

    public function getPaymentById( int $paymentId  )
    {
        $sql = "  SELECT * FROM payments WHERE payment_id = ?";

        return $this->db->query($sql, [$paymentId])->getRowArray();
    }
}