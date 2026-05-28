<?php

namespace App\User\Model\Auth;


class UserModel
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function addUser($data) : bool
    {
        $sql = "INSERT INTO users 
    (
        first_name,
        last_name,
        email,
        phone,
        dept_id,
        group_id,
        manager_id,
        status
    ) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $this->db->query($sql, [
            $data['first_name'],
            $data['last_name'],
            $data['email'],
            $data['phone'],
            $data['dept_id'],
            $data['group_id'],
            $data['manager_id'],
            $data['role'],
            $data['status']
        ]);

        $id = $this->db->insertID();

        

        $prevHierarchy[] = $id;

        $newHierarchy = json_encode($prevHierarchy,true);

        $sql3 = "UPDATE users 
             SET hierarchy = ?
             WHERE id = ?";

        return $this->db->query($sql3, [
            $newHierarchy,
            $id
        ]);

    }
    public function createUserGroup($data): bool
    {
        $sql = "INSERT INTO user_groups
            (
                group_name,
                manager_id,
                dept_id
            )
            VALUES (?, ?, ?)";

        return $this->db->query($sql, [
            $data['group_name'],
            $data['manager_id'],
            $data['dept_id']
        ]);
    }

    public function getAllUser() : array
    {
        $sql = "Select * from users";
        return $this->db->query($sql)->getResultArray();
    }

    public function getSpecificUser($id): array
    {
        $sql = "Select * from users where id=?";
        return $this->db->query($sql, [$id])->getRowArray();
    }

    public function getGroupMembers($id): array
    {
        $sql = "Select * from users where group_id=?";
        return $this->db->query($sql, [$id])->getRowArray();
    }

    public function getHierarchy($userId)
    {
        
    }
}