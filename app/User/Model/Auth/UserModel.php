<?php

namespace App\User\Model\Auth;

use Config\Database;
use CodeIgniter\Database\BaseConnection;

class UserModel
{
    /**
     * Database connection instance.
     *
     * @var BaseConnection
     */
    protected BaseConnection $db;

    /**
     * Constructor.
     * Initializes database connection.
     */
    public function __construct()
    {
        $this->db = Database::connect();
    }

    /**
     * Add a new user to the database.
     *
     * @param array $data User data
     *
     * @return int Inserted user ID
     */
    public function addUser(array $data): int
    {
        $sql = "INSERT INTO users 
        (
            first_name,
            last_name,
            email,
            phone,
            dept_id,
            group_id,
            status
        ) 
        VALUES (?, ?, ?, ?, ?, ?, ?)";

        $this->db->query($sql, [
            $data['first_name'],
            $data['last_name'],
            $data['email'],
            $data['phone'],
            $data['dept_id'],
            $data['group_id'],
            $data['status']
        ]);

        return $this->db->insertID();
    }

    /**
     * Create a new user group.
     *
     * @param array $data Group data
     *
     * @return bool
     */
    public function createUserGroup(array $data): bool
    {
        $sql = "INSERT INTO user_groups
        (
            group_name,
            path,
            dept_id
        )
        VALUES (?, ?, ?)";

        return $this->db->query($sql, [
            $data['group_name'],
            $data['path'],
            $data['dept_id']
        ]);
    }

    /**
     * Fetch all users.
     *
     * @return array
     */
    public function getAllUser(): array
    {
        $sql = "SELECT * FROM users";

        return $this->db->query($sql)->getResultArray();
    }

    /**
     * Fetch a specific user by ID.
     *
     * @param int $id User ID
     *
     * @return array
     */
    public function getSpecificUser(int $id): array
    {
        $sql = "SELECT * FROM users WHERE id = ?";

        return $this->db->query($sql, [$id])->getRowArray();
    }

    /**
     * Fetch all members of a specific group.
     *
     * @param int $id Group ID
     *
     * @return array
     */
    public function getGroupMembers(int $id): array
    {
        $sql = "SELECT * FROM users WHERE group_id = ?";

        return $this->db->query($sql, [$id])->getResultArray();
    }

    /**
     * Set hierarchy data for a user.
     *
     * If the path is null, the user is considered a top-level user.
     * Otherwise, hierarchy level is calculated based on parent level.
     *
     * @param int      $userId User ID
     * @param int|null $path   Parent user ID
     *
     * @return bool
     */
    public function setHierarchy(int $userId, ?int $path): bool
    {
        if ($path === null) {

            $sql = "INSERT INTO hierarchy 
                    (
                        user_id,
                        path,
                        level
                    ) 
                    VALUES (?, ?, ?)";

            return $this->db->query($sql, [$userId, $path, 0]);
        }

        $sql = "SELECT level FROM hierarchy WHERE user_id = ?";

        $query = $this->db->query($sql, [$path])->getRowArray();

        $managerLevel = $query['level'];

        $userLevel = $managerLevel + 1;

        $sql2 = "INSERT INTO hierarchy 
                (
                    user_id,
                    path,
                    level
                ) 
                VALUES (?, ?, ?)";

        return $this->db->query($sql2, [
            $userId,
            $path,
            $userLevel
        ]);
    }

    /**
     * Fetch hierarchy details recursively for a user.
     *
     * @param int $userId User ID
     *
     * @return array
     */
    public function getHierarchy(int $userId): array
    {
        $sql = "SELECT 
                    path,
                    level,
                    user_id
                FROM hierarchy
                WHERE user_id = ?";

        $query = $this->db->query($sql, [$userId])->getRowArray();

        if (!$query) {
            return [];
        }

        $data = [
            [
                "user_id" => $query['user_id'],
                "level"   => $query['level'],
                "path"    => $query['path']
            ]
        ];

        // Recursively fetch parent hierarchy
        if (!empty($query['path'])) {

            $parentHierarchy = $this->getHierarchy((int) $query['path']);

            $data = array_merge($data, $parentHierarchy);
        }

        return $data;
    }
}