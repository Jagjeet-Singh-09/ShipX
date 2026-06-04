<?php

namespace App\Vendor\Service\Announcement;

use App\Vendor\Model\Announcement\AnnouncementModel;

class AnnouncementService
{
    protected $AnnouncementModel;

    public function __construct()
    {
        $this->AnnouncementModel = new AnnouncementModel();
        

    }

    public function getAllAnnouncement($id){
        $data = $this->AnnouncementModel->getAllAnnouncement($id);
       
        return $data;
    }
}