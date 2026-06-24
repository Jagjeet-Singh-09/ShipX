<?php

namespace App\User\Service\Announcement;
use App\Helpers\ApiResponseHelper;
use App\User\Model\Announcement\AnnouncementModel;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * Service for announcement business operations.
 */
class AnnouncementService
{
    /**
     * Announcement model instance.
     *
     * @var AnnouncementModel
     */
    protected AnnouncementModel $AnnouncementModel;

    public function __construct()
    {
        $this->AnnouncementModel = new AnnouncementModel();
    }

    public function createAnnouncement(array $data, int $id): ResponseInterface{

        if($data["inclusions"] === null) {
            $data["inclusions"] = [];
        }

        $inclusionsLength = count($data["inclusions"]);
        $validGroupNo=[0,1,2,3];

        
        if($data['target_group_id'] !== 0 && $inclusionsLength > 0) {
            return ApiResponseHelper::apiResponseHandler("Please Add group_no = 0 if you want to use inclusions option",null,400);
            
        }
        
        if(!in_array($data['target_group_id'] , $validGroupNo)) {
            return ApiResponseHelper::apiResponseHandler("Please Add a valid group number",null,400);
        }

        $result = $this->AnnouncementModel->createAnnouncement($data, $id);

        //$this->setAnnouncement($data['target_group_id'],$announcement_id);
        return ApiResponseHelper::apiResponseHandler("Insertation Successfully",$result,200);
    
    
    }

     public function deleteAnnouncement(int $id): ResponseInterface{
        $res=$this->AnnouncementModel->deleteAnnouncement($id);
        if(!$res){
            return ApiResponseHelper::apiResponseHandler("Deletation failed",null,400);
        }
        return ApiResponseHelper::apiResponseHandler("Deletation Successfully",$res,200);
    
    }

    public function getAllAnnouncement(): ResponseInterface{
        $res = $this->AnnouncementModel->getAllAnnouncement();
        if(!$res){
            return ApiResponseHelper::apiResponseHandler("Not data found",null,400);
        }
        return ApiResponseHelper::apiResponseHandler("Data Fetched Successfully",$res,200);
    }    

     public function updateAnnouncement(array $data): ResponseInterface{

        $res = $this->AnnouncementModel->updateAnnouncement($data);
        if(!$res){
            return ApiResponseHelper::apiResponseHandler("Data not updating","Data not updating",null,400);
        }
        return ApiResponseHelper::apiResponseHandler("Data updating Successfully",$res,200);


    }

    // public function setAnnouncement($target_group_id,$Announcement_id){
    //     return $this->AnnouncementModel->setAnnouncement($target_group_id,$Announcement_id);
    // }

}