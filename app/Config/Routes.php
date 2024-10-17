<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */


$routes->post('register', 'UserController::register');
$routes->post('login', 'UserController::login');
$routes->get('admin','AdminController::Admindashboard');
$routes->get('user','AdminController::UserDashboard');
$routes->post('admin/add', 'AdminController::addUser');                
$routes->post('admin/update/(:num)', 'AdminController::updateUser/$1'); 
$routes->post('admin/delete/(:num)', 'AdminController::deleteUser/$1'); 



