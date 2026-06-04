<?php

namespace App\User\Model\VendorKyc;

use Config\Database;

/**
 * Model for vendor KYC database operations.
 */
class VendorKycModel
{
    /**
     * Database connection instance.
     *
     * @var \CodeIgniter\Database\ConnectionInterface
     */
    protected $db;

    /**
     * Model constructor.
     *
     * Connects to the database.
     */
    public function __construct()
    {
        $this->db = Database::connect();
    }

    /**
     * Fetch paginated vendor KYC records.
     *
     * @param int $limit Number of records per page
     * @param int $offset Query offset
     * @return array
     */
    public function getAllVendor(int $limit, int $offset): array
    {
        $limit = (int) $limit;
        $offset = (int) $offset;

        $sql = "SELECT * 
            FROM vendor_docs AS vd
            INNER JOIN vendors AS v
            ON vd.vendor_id = v.id
            LIMIT {$limit} OFFSET {$offset}";

        $query = $this->db->query($sql);

        return $query->getResultArray();
    }

    /**
     * Fetch document details for a single vendor.
     *
     * @param int $id Vendor ID
     * @return array|null
     */
    public function getVendorDocuments(int $id): ?array
    {
        $sql = "SELECT 

                aadhar_card,
                aadhar_card_id,
                aadhar_card_path,
                aadhar_card_status,

                pan_card,
                pan_card_id,
                pan_card_path,
                pan_card_status,

                gst_number,
                gst_id,
                gst_path,
                gst_status,

                final_status,
                remarks

            FROM vendor_docs

            WHERE vendor_id = ?";

        $query = $this->db->query($sql, [$id]);

        return $query->getRowArray();
    }

    /**
     * Update status for a single vendor document field.
     *
     * @param int $id Vendor ID
     * @param string $status New status value
     * @param string $type Status field name
     * @return array
     */
    public function updateVendorStatus(int $id, string $status, string $type): array
    {
        if ($type === "aadhar_card_status") {
            $sql = "UPDATE vendor_docs
                SET aadhar_card_status = ?
                WHERE vendor_id = ?";

            $result = $this->db->query($sql, [
                $status,
                $id
            ]);
        } elseif ($type === "pan_card_status") {
            $sql = "UPDATE vendor_docs
                SET pan_card_status = ?
                WHERE vendor_id = ?";

            $result = $this->db->query($sql, [
                $status,
                $id
            ]);
        } elseif ($type === "gst_status") {
            $sql = "UPDATE vendor_docs
                SET gst_status = ?
                WHERE vendor_id = ?";

            $result = $this->db->query($sql, [
                $status,
                $id
            ]);
        } else {
            $result = null;
        }

        return [
            'success' => (bool) $result,
            'message' => 'Vendor Status Updated Successfully',
        ];
    }

    /**
     * Update the vendor KYC remarks field.
     *
     * @param int $id Vendor ID
     * @param string $remarks Remarks text
     * @return array
     */
    public function updateRemarks(int $id, string $remarks): array
    {
        $sql = "UPDATE vendor_docs
            SET remarks = ?
            WHERE vendor_id = ?";

        $result = $this->db->query($sql, [
            $remarks,
            $id
        ]);

        return [
            'success' => (bool) $result,
            'message' => 'Remarks Updated Successfully',
        ];
    }

    /**
     * Retrieve the current vendor final KYC status.
     *
     * @param int $id Vendor ID
     * @return array|null
     */
    public function getFinalStatus(int $id): ?array
    {
        $sql = "SELECT * FROM vendor_docs WHERE vendor_id = ?";

        $query = $this->db->query($sql, [$id]);

        return $query->getRowArray();
    }

    /**
     * Persist the computed final KYC status for a vendor.
     *
     * @param int $id Vendor ID
     * @param string $finalStatus Computed status value
     * @return array
     */
    public function updateFinalStatus(int $id, string $finalStatus): array
    {
        $sql = "UPDATE vendor_docs
                SET final_status = ?
                WHERE vendor_id = ?";

        $result = $this->db->query($sql, [
            $finalStatus,
            $id
        ]);

        return [
            'success' => (bool) $result,
            'message' => 'Final Status Updated Successfully',
        ];
    }
}
