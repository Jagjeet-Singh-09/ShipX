<?php

use CodeIgniter\Router\RouteCollection;
use App\User\Controller\Auth;

/** @var RouteCollection $routes */
$routes->get('/', 'Home::index');

$routes->post('/api/superAdmin/register', '\App\User\Controller\Auth\AuthController::createUser');
$routes->post('/api/superAdmin/login', '\App\User\Controller\Auth\AuthController::checkLogIn');

$routes->post('/addUser', '\App\User\Controller\Auth\UserController::addUser');
$routes->post('/addGroups', '\App\User\Controller\Auth\UserController::createUserGroup');
$routes->get('/getAllUsers', '\App\User\Controller\Auth\UserController::getAllUser');
$routes->get('getUser/(:num)', '\App\User\Controller\Auth\UserController::getSpecificUser/$1');
$routes->get('getGroupMembers/(:num)', '\App\User\Controller\Auth\UserController::getGroupMembers/$1');