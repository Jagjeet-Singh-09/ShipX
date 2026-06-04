<?php

namespace App\Vendor\Model\Announcement;

use Config\Database;

class AnnouncementModel
{
    protected $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

   public function getAllAnnouncement($id)
{
    $currentDateAndTime = date('Y-m-d H:i:s');

    $sql = "
        SELECT *
        FROM announcements a
        LEFT JOIN target_group tg
            ON a.target_group_id = tg.id
        WHERE starting_datetime <= ?
        AND ending_datetime >= ?
    ";

    $announcements = $this->db->query(
        $sql,
        [$currentDateAndTime, $currentDateAndTime]
    )->getResultArray();

    $result = [];

    foreach ($announcements as $announcement) {

        $inclusions = json_decode($announcement['inclusions'], true) ?? [];
        $exclusions = json_decode($announcement['exclusions'], true) ?? [];

        if (in_array($id, $exclusions)) {
            continue;
        }

        if (in_array($announcement['group_no'], [1, 3])) {
            $result[] = $announcement;
        }

        if (
            (int)$announcement['group_no'] === 0 &&
            in_array($id, $inclusions)
        ) {
            $result[] = $announcement;
        }
    }

    return $result;
}
}
