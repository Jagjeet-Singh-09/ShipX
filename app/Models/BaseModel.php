<?php
namespace App\Models;
use Config\Database;

class BaseModel
{
    // Base model functionality can be added here
    protected $db;
    
    public function __construct()
    {
        $this->db = Database::connect();
    }
}