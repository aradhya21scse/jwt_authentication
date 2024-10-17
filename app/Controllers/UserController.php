<?php

namespace App\Controllers;
use CodeIgniter\Controller;

use App\Models\UserModel;
use App\Controllers\UserValidation;
use Firebase\JWT\JWT;
use App\Controllers\MessageController;

class UserController extends BaseController
{
    public  $userModel;
    public $validator;

    public $messageController;


    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->validator = new UserValidation();
        $this->messageController = new MessageController();

    }


    public function register() //register function to register user 
    {
        $data = $this->request->getJSON(true);

        $Result = $this->validator->validateReg($data);//object of validateReg function in UserValidation Controller
        if (!$Result) {
            return $this->response->setJSON($Result)->setStatusCode(400);
        }
        $existingUser = $this->userModel->getUserDataByEmail($data['email']); //check for existing user son they cannot register again
        if ($existingUser) {
            return $this->messageController->displayMessage('false', 'Email already exist.');
         }

        $userData = [
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => password_hash($data['password'], PASSWORD_DEFAULT), 
        ];

        $this->userModel->saveUserDataInDB($userData); //model function is used to insert data
        return $this->messageController->displayMessage('true', 'User registered successfully.');
    }

    public function login()//login function to login and token generation
    {
        $data = $this->request->getJSON(true);
        $Result = $this->validator->validateLogin($data);
        if (!$Result    ) {
            return $this->response->setJSON($Result)->setStatusCode(400);
        }
        if (!isset($data['email']) || !isset($data['password'])) {
            return $this->messageController->displayMessage('false', 'Email and password are required.');
        }
    
    
        $user = $this->userModel->getUserDataByEmail($data['email']);
        if (!$user || !password_verify($data['password'], $user->password)) {   
            return $this->messageController->displayMessage('false', 'Invalid email or password.');  
        }
    
        $payload = [
            'id' => $user->id,
            'username' => $user->username,
            'role' => $user->role
        ];
    
        $jwt = JWT::encode($payload, getenv('JWT_SECRET'), getenv('JWT_HASH_ALGO')); //jwt  token is generated here

        return $this->response->setJSON(['status' => true, 'token' => $jwt]);
    }
    
}
