<?php

namespace App\Vendor\Model\Auth;

use Config\Database;

class VendorAuthModel
{
    protected $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function vendorRegister($data)  
    {

        $sql = "INSERT INTO vendors 
                (phone, email, password,role)
                VALUES (?, ?, ? ,?)";

        $query=$this->db->query($sql, [
            $data['phone'],
            $data['email'],
            $data['password'],
            1
        ]);
        $sql2 = "SELECT * FROM vendors WHERE id = ?";
        $query2 = $this->db->query($sql2, [$this->db->insertID()]);
        return $query2->getRowArray();
    }

    public function getDataByMail($email)
    {
        $sql = "SELECT * FROM vendors WHERE email = ?";

        $query = $this->db->query($sql, [$email]);

        return $query->getRowArray();
    }

    public function stepOneData($data, $id)
    {

        $sql = "UPDATE vendors SET first_name = ?, last_name = ?, date_of_birth=? WHERE id = ?";
        $querry = $this->db->query($sql, [$data['first_name'],$data['last_name'],$data['dob'],$id]);
        $sql2 = "UPDATE vendor_register_steps SET step_one = CURRENT_TIMESTAMP WHERE user_id = ?;";
        return $this->db->query($sql2, [$id]);
    }

    public function stepTwoData($data)
    {

        $sql = "UPDATE vendors SET permanent_address = ?,shipping_address = ? WHERE id = ?";
        $querry = $this->db->query($sql, [$data['permanentAddress'],$data['shippingAddress'],$data['id']]);
        $sql2 = "UPDATE vendor_register_steps SET step_two = CURRENT_TIMESTAMP WHERE user_id = ?;";
        return $this->db->query($sql2, [ $data['id']]);
    }

    public function stepThreeData($data, $id)
    {
        // AUTO GENERATED IDS
        $aadharCardId = uniqid();
        $panCardId    = uniqid();
        $gstId        = uniqid();

        $sql = "INSERT INTO vendor_docs (vendor_id,aadhar_card_id,pan_card_id,gst_id,aadhar_card,aadhar_card_path,pan_card,
        pan_card_path,gst_number,gst_path,aadhar_card_status,pan_card_status,gst_status,final_status,remarks
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $query = $this->db->query($sql, [$id,$aadharCardId,$panCardId,$gstId,$data['aadharCardNumber'],$data['aadharCardImage'],
        $data['panCardNumber'],$data['panCardImage'],$data['gstNumber'],$data['gst'],'pending','pending','pending','pending',null
        ]);

        $sql2 = "UPDATE vendor_register_steps SET step_two = CURRENT_TIMESTAMP WHERE user_id = ?;";
        return $this->db->query($sql2, [$id]);

    }

}