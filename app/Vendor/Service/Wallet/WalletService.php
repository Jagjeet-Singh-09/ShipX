<?php

namespace App\Vendor\Service\Wallet;

use App\Vendor\Model\Wallet\WalletModel;

class WalletService
{
    public function credit(int $vendorId,float $amount)
    {
        $walletModel = new WalletModel();

        $wallet = $walletModel->getWalletByVendorId($vendorId);

        $walletModel->creditWallet($wallet['wallet_id'],$amount);

        }
}