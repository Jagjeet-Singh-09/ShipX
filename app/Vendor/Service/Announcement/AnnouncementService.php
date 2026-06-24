<?php

namespace App\Vendor\Service\Announcement;

use App\Vendor\Model\Announcement\AnnouncementModel;

class AnnouncementService
{
    protected AnnouncementModel $AnnouncementModel;

    public function __construct()
    {
        $this->AnnouncementModel = new AnnouncementModel();
        

    }

    public function getAllAnnouncement(int $id){
        $data = $this->AnnouncementModel->getAllAnnouncement($id);
       
        return $data;
    }
}