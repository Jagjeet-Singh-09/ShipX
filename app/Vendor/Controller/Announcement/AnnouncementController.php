<?php

namespace App\Vendor\Controller\Announcement;

//use App\Vendor\Controllers\BaseController;
use App\Vendor\Service\Announcement\AnnouncementService;
use App\Controllers\BaseController;



class AnnouncementController extends BaseController
{
    protected $announcementService;


    public function __construct()
    {
        $this->announcementService = new AnnouncementService();
    }

    public function getAllAnnouncement($id){
        $data= $this->announcementService->getAllAnnouncement($id);
        return $this->response
            ->setStatusCode(200)
            ->setJSON($data);
    }

}