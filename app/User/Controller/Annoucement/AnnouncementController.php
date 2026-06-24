<?php

namespace App\User\Controller\Annoucement;

use App\Controllers\BaseController;
use App\User\Service\Announcement\AnnouncementService;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * Controller for announcement-related actions in the user area.
 *
 * This controller delegates announcement business logic to the
 * AnnouncementService and returns HTTP responses.
 */
class AnnouncementController extends BaseController
{
    /**
     * Service responsible for announcement CRUD operations.
     *
     * @var AnnouncementService
     */
    protected AnnouncementService $AnnouncementService;
    //protected $adminValidations;

    /**
     * AnnouncementController constructor.
     *
     * Initializes the AnnouncementService instance used by this controller.
     */
    public function __construct()
    {
        $this->AnnouncementService = new AnnouncementService();
        //$this->adminValidations = new AdminValidations();
    }

    /**
     * Create a new announcement.
     *
     * Reads JSON data from the request body and passes it to the service
     * together with the current user id from session.
     *
     * @return ResponseInterface
     */
    public function createAnnouncement(): ResponseInterface
    {
        // Decode JSON request body into an associative array.
        $data = $this->request->getJSON(true);

        // Retrieve the currently authenticated user id from session.
        $id = session()->get('id');

        // Delegate creation to the AnnouncementService.
        return $this->AnnouncementService->createAnnouncement($data, $id);
    }

    /**
     * Delete an announcement by its id.
     *
     * @param mixed $id The identifier of the announcement to delete.
     * @return ResponseInterface
     */
    public function deleteAnnouncement($id): ResponseInterface
    {
        return $this->AnnouncementService->deleteAnnouncement($id);
    }

    /**
     * Retrieve all announcements.
     *
     * @return ResponseInterface
     */
    public function getAllAnnouncement(): ResponseInterface
    {
        return $this->AnnouncementService->getAllAnnouncement();
    }

    /**
     * Edit an existing announcement.
     *
     * Reads JSON request body and delegates update logic.
     * NOTE: This method currently returns all announcements instead of
     * fetching a single announcement for editing.
     *
     * @param mixed $id The identifier of the announcement to edit.
     * @return ResponseInterface
     */
    public function editAnnouncement($id): ResponseInterface
    {
        $data = $this->request->getJSON(true);

        // TODO: Replace the following line with a proper edit operation.
        return $this->AnnouncementService->getAllAnnouncement();
    }

    /**
     * Update an existing announcement.
     *
     * Reads JSON payload from the request body and forwards it to the service.
     *
     * @return ResponseInterface
     */
    public function updateAnnouncement(): ResponseInterface
    {
        $data = $this->request->getJSON(true);

        return $this->AnnouncementService->updateAnnouncement($data);
    }
}
