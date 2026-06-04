<?php

namespace App\User\Service\VendorKyc;

use App\User\Model\VendorKyc\VendorKycModel;

/**
 * Service for vendor KYC business logic.
 */
class VendorKycService
{
    /**
     * Vendor KYC model instance.
     *
     * @var VendorKycModel
     */
    protected VendorKycModel $vendorKycModel;

    /**
     * Service constructor.
     *
     * Initializes the vendor KYC model.
     */
    public function __construct()
    {
        $this->vendorKycModel = new VendorKycModel();
    }

    /**
     * Retrieve paginated vendor records.
     *
     * @param int $limit Number of records per page
     * @param int $page Page number
     * @return array
     */
    public function getAllVendor(int $limit, int $page): array
    {
        $offset = ($page - 1) * $limit;

        return $this->vendorKycModel->getAllVendor($limit, $offset);
    }

    /**
     * Retrieve vendor document details.
     *
     * @param int $id Vendor ID
     * @return array|null
     */
    public function getVendorDocuments(int $id): ?array
    {
        return $this->vendorKycModel->getVendorDocuments($id);
    }

    /**
     * Update the vendor KYC remarks.
     *
     * @param int $id Vendor ID
     * @param string $remarks Remarks text
     * @return array
     */
    public function updateRemarks(int $id, string $remarks): array
    {
        $result = $this->vendorKycModel->updateRemarks($id, $remarks);

        return $result;
    }

    /**
     * Update a vendor document status and recompute final status.
     *
     * @param int $id Vendor ID
     * @param string $status New document status
     * @param string $type Field to update
     * @return array
     */
    public function updateVendorStatus(int $id, string $status, string $type): array
    {
        $result = $this->vendorKycModel->updateVendorStatus($id, $status, $type);
        $this->updateFinalStatus($id);

        return $result;
    }

    /**
     * Compute and persist the vendor's final KYC status.
     *
     * @param int $id Vendor ID
     * @return void
     */
    public function updateFinalStatus(int $id): void
    {
        $data = $this->vendorKycModel->getFinalStatus($id);

        if ($data['aadhar_card_status'] === 'approved' && $data['pan_card_status'] === 'approved' && $data['gst_status'] === 'approved') {
            $finalStatus = 'approved';
        } elseif ($data['aadhar_card_status'] === 'rejected' || $data['pan_card_status'] === 'rejected' || $data['gst_status'] === 'rejected') {
            $finalStatus = 'rejected';
        } else {
            $finalStatus = 'pending';
        }

        $this->vendorKycModel->updateFinalStatus($id, $finalStatus);
    }

}
