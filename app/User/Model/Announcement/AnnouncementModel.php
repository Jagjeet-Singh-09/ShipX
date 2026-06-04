<?php

namespace App\User\Model\Announcement;

use Config\Database;
use App\Models\BaseModel;

/**
 * Model for announcement persistence operations.
 */
class AnnouncementModel extends BaseModel
{
    public function createAnnouncement(array $data, int $id)
    {
        $inclusions = json_encode($data['inclusions']);
        $exclusions = json_encode($data['exclusions']);

        $sql2 = "INSERT INTO target_group (group_no,inclusions,exclusions) VALUES (?, ?, ?)";
        $this->db->query($sql2, [$data['target_group_id'], $inclusions, $exclusions]);

        $target_id = $this->db->insertID();

        $sql = "INSERT INTO announcements (title, description, starting_datetime, ending_datetime, admin_id, target_group_id) VALUES (?, ?, ?, ?, ?, ?)";
        $query = $this->db->query($sql, [$data['title'], $data['description'], $data['starting_datetime'], $data['ending_datetime'], $id, $target_id]);

        return $query;
    }

    public function deleteAnnouncement(int $id)
    {
        $sql = "DELETE FROM announcements WHERE id = ?";
        return $this->db->query($sql, [$id]);
    }

    public function getAllAnnouncement(): array
    {
        $sql = "SELECT * FROM announcements";
        $query = $this->db->query($sql);
        return $query->getResultArray();
    }

    public function updateAnnouncement(array $data): bool
    {
        $sql = "SELECT * FROM announcements WHERE id = ?";

        $query = $this->db->query($sql, [$data['id']]);
        $oldData = $query->getRowArray();

        $title = ($data['title'] ?? null) ? $data['title'] : $oldData['title'];
        $starting_datetime = ($data['starting_datetime'] ?? null) ? $data['starting_datetime'] : $oldData['starting_datetime'];
        $ending_datetime = ($data['ending_datetime'] ?? null) ? $data['ending_datetime'] : $oldData['ending_datetime'];

        $sql = "
            UPDATE announcements
            SET title = ?,
                starting_datetime = ?,
                ending_datetime = ?
            WHERE id = ?
        ";

        $this->db->query($sql, [
            $title,
            $starting_datetime,
            $ending_datetime,
            $data['id'],
        ]);

        return true;
    }
}
