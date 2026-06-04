<?php

namespace App\User\Controller\VendorKyc;

use App\Controllers\BaseController;
use App\User\Service\VendorKyc\VendorKycService;
use CodeIgniter\HTTP\ResponseInterface;





/**
 * Controller for vendor KYC-related endpoints.
 */
class VendorKycController extends BaseController
{
    /**
     * Vendor KYC service instance.
     *
     * @var VendorKycService
     */
    protected VendorKycService $vendorKycService;

    /**
     * Controller constructor.
     *
     * Initializes the vendor KYC service.
     */
    public function __construct()
    {
        $this->vendorKycService = new VendorKycService();
    }

    /**
     * Return paginated vendor records.
     *
     * @return ResponseInterface
     */
    public function getAllVendor(): ResponseInterface
    {
        $data = $this->request->getJSON(true);
        $limit = (int) $data['limit'];
        $page = (int) $data['page'];
        $vendorData = $this->vendorKycService->getAllVendor($limit, $page);

        return $this->response->setJSON([
            'status' => 'success',
            'page' => $page,
            'limit' => $limit,
            'data' => $vendorData,
        ]);
    }

    /**
     * Retrieve document metadata for a given vendor.
     *
     * @param int $id Vendor ID
     * @return ResponseInterface
     */
    public function getVendorDocuments(int $id): ResponseInterface
    {
        $result = $this->vendorKycService->getVendorDocuments($id);

        if (!$result) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'No documents found',
            ]);
        }

        return $this->response->setJSON([
            'status' => true,
            'aadhar_card' => $result['aadhar_card'],
            'aadhar_card_id' => $result['aadhar_card_id'],
            'aadhar_card_path' => base_url($result['aadhar_card_path']),
            'aadhar_card_status' => $result['aadhar_card_status'],
            'pan_card' => $result['pan_card'],
            'pan_card_id' => $result['pan_card_id'],
            'pan_card_path' => base_url($result['pan_card_path']),
            'pan_card_status' => $result['pan_card_status'],
            'gst_number' => $result['gst_number'],
            'gst_id' => $result['gst_id'],
            'gst_path' => base_url($result['gst_path']),
            'gst_status' => $result['gst_status'],
            'final_status' => $result['final_status'],
            'remarks' => $result['remarks'],
        ]);
    }

    /**
     * Update remarks for a vendor KYC record.
     *
     * @param int $id Vendor ID
     * @return ResponseInterface
     */
    public function updateRemarks(int $id): ResponseInterface
    {
        $data = $this->request->getJSON(true);
        $remarks = $data['remarks'];

        $result = $this->vendorKycService->updateRemarks($id, $remarks);

        return $this->response->setJSON($result);
    }

    /**
     * Update vendor document status and refresh final vendor status.
     *
     * @param int $id Vendor ID
     * @return ResponseInterface
     */
    public function updateVendorStatus(int $id): ResponseInterface
    {
        $data = $this->request->getJSON(true);
        $status = $data['status'];
        $type = $data['type'];

        $result = $this->vendorKycService->updateVendorStatus($id, $status, $type);

        return $this->response->setJSON($result);
    }
}
