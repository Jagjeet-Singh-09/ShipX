<?php

namespace App\User\Model\Auth;

use App\Models\BaseModel;

/**
 * Model for user-related database operations.
 */
class UserModel extends BaseModel
{
    /**
     * Create a new user group.
     *
     * @param array $data Group data
     *
     * @return bool
     */
    public function createUserGroup(array $data): bool
    {
        $sql = "INSERT INTO user_groups (group_name, manager_id, dept_id) VALUES (?, ?, ?)";

        return $this->db->query($sql, [$data['group_name'], $data['manager_id'], $data['dept_id']]);
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
     * @return array|null
     */
    public function getSpecificUser(int $id): ?array
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


        $sql = "INSERT INTO hierarchy (user_id,path) VALUES (?, ?)";

        return $this->db->query($sql, [$userId,$path]);
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
        $sql = "SELECT path, user_id FROM hierarchy WHERE user_id = ?";

        $query = $this->db->query($sql, [$userId])->getRowArray();

        if (!$query) {
            return [];
        }

        $data = [
            [
                "user_id" => $query['user_id'],
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
