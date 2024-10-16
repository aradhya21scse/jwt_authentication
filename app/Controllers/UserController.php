<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Controllers\UserValidation;
use Firebase\JWT\JWT;

class UserController extends BaseController
{
    protected  $userModel;
    protected $validator;


    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->validator = new UserValidation();
    }


    public function register()
    {
        $data = $this->request->getJSON(true);

        $Result = $this->validator->validateReg($data);
        if (!$Result) {
            return $this->response->setJSON($Result)->setStatusCode(400);
        }
        $existingUser = $this->userModel->getUserDataByEmail($data['email']);
        if ($existingUser) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Email already exists.'
            ])->setStatusCode(400);
         }

        $userData = [
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => password_hash($data['password'], PASSWORD_DEFAULT),
        ];

        $this->userModel->saveUserDataInDB($userData);
        return $this->response->setJSON(['status' => true, 'message' => 'User registered successfully.']);
    }

    public function login()
    {
        $data = $this->request->getJSON(true);
        $Result = $this->validator->validateLogin($data);
        if (!$Result    ) {
            return $this->response->setJSON($Result)->setStatusCode(400);
        }
        if (!isset($data['email']) || !isset($data['password'])) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Email and password are required.'
            ])->setStatusCode(400);
        }
    
    
        $user = $this->userModel->getUserDataByEmail($data['email']);
        if (!$user || !password_verify($data['password'], $user->password)) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Invalid email or password.'
            ])->setStatusCode(401);
        }
    
        $payload = [
            'id' => $user->id,
            'username' => $user->username,
            'role' => $user->role
        ];
    
        $jwt = JWT::encode($payload, getenv('JWT_SECRET'), 'HS256');
        return $this->response->setJSON(['status' => true, 'token' => $jwt]);
    }
    
}
