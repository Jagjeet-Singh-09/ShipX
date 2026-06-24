<?php

namespace App\Vendor\Model\Wallet;

use CodeIgniter\Model;

class WalletModel extends Model
{
    public function getWalletByVendorId(
        int $vendorId
    ) {
        $sql = "SELECT * FROM wallets WHERE vendor_id = ? ";

        return $this->db->query($sql, [$vendorId])->getRowArray();
    }

    public function creditWallet(int $walletId,float $amount ) 
    {
        
        $sql = " UPDATE wallets SET balance = balance + ? WHERE wallet_id = ? ";

        return $this->db->query($sql, [$amount, $walletId]);
    }
}