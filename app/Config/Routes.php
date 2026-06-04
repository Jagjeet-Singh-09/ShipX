<?php

use CodeIgniter\Router\RouteCollection;
use App\User\Controller\Auth;

/** @var RouteCollection $routes */
$routes->get('/', 'Home::index');

$routes->post('/api/superAdmin/register', '\App\User\Controller\Auth\AuthController::createUser');
$routes->post('/api/superAdmin/login', '\App\User\Controller\Auth\AuthController::checkLogIn');

$routes->post('/addUser', '\App\User\Controller\Auth\AuthController::createUser');
$routes->post('/addGroups', '\App\User\Controller\Auth\UserController::createUserGroup');
$routes->get('/getAllUsers', '\App\User\Controller\Auth\UserController::getAllUser');
$routes->get('getUser/(:num)', '\App\User\Controller\Auth\UserController::getSpecificUser/$1');
$routes->get('getGroupMembers/(:num)', '\App\User\Controller\Auth\UserController::getGroupMembers/$1');
$routes->get('getHierarchy/(:num)', '\App\User\Controller\Auth\UserController::getHierarchy/$1');

$routes->get('/api/user/getAllVendor', '\App\User\Controller\VendorKyc\VendorKycController::getAllVendor');
$routes->get('api/user/vendor-documents/(:num)','\App\User\Controller\VendorKyc\VendorKycController::getVendorDocuments/$1');
$routes->post('api/user/update-remarks/(:num)', '\App\User\Controller\VendorKyc\VendorKycController::updateRemarks/$1');
$routes->post('api/user/update-vendor-status/(:num)', '\App\User\Controller\VendorKyc\VendorKycController::updateVendorStatus/$1');

$routes->post('/api/user/createAnnouncement', '\App\User\Controller\Annoucement\AnnouncementController::createAnnouncement');
$routes->post('/api/user/deleteAnnouncement/(:num)', '\App\User\Controller\Annoucement\AnnouncementController::deleteAnnouncement/$1');
$routes->get('/api/user/getAllAnnouncement', '\App\User\Controller\Annoucement\AnnouncementController::getAllAnnouncement');
$routes->post('/api/user/updateAnnouncement', '\App\User\Controller\Annoucement\AnnouncementController::updateAnnouncement');
$routes->post('/api/vendor/getAnnouncements/(:num)', '\App\Vendor\Controller\Announcement\AnnouncementController::getAllAnnouncement/$1');



$routes->post('/api/vendor/register', '\App\Vendor\Controller\Auth\VendorAuthController::vendorRegister');
$routes->post('/api/vendor/stepOneData', '\App\Vendor\Controller\Auth\VendorAuthController::stepOneData');
$routes->post('/api/vendor/stepTwoData', '\App\Vendor\Controller\Auth\VendorAuthController::stepTwoData');
$routes->post('/api/vendor/stepThreeData', '\App\Vendor\Controller\Auth\VendorAuthController::stepThreeData');


