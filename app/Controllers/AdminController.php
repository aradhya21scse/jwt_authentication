<?php

namespace App\Controllers;

use App\Models\UserModel;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AdminController extends BaseController
{
    protected $userModel;

    public function __construct() 
    {
        $this->userModel = new UserModel();
    }

    private function validateJWT($token)//function to validate and 
{
    try {
        $decoded = JWT::decode($token, new Key(getenv('JWT_SECRET'), 'HS256'));
        return $decoded;
    } catch (\Exception $e) {
        return false;
    }
}


<<<<<<< HEAD
private function requireAdmin() //check for admin 
=======
private function requireAdmin()//to check for admin
>>>>>>> 51a653a551d62af92357d969975133c174d91a54
{
    $header = $this->request->getHeaderLine('Authorization');   //getting authourization header
    $token = str_replace('Bearer ', '', $header); //removing bearer and extracting only the token part from the header
    $decoded = $this->validateJWT($token);

    if (!$decoded || $decoded->role !== 'admin') {
        return $this->response->setJSON([
            'status' => false,
            'message' => 'Unauthorized access. Admins only.'
        ])->setStatusCode(403);
    }
    return true; 
}

    private function requireUser()//to check for user
    {
        $header = $this->request->getHeaderLine('Authorization');
        $token = str_replace('Bearer ', '', $header);
        $decoded = $this->validateJWT($token);

        if (!$decoded) {
            return false; 
        }

        return $decoded; 
    }
    public function UserDashboard()//user dashboard access and displaying details
        {
            $decoded = $this->requireUser();
            if (!$decoded) {
                return $this->response->setJSON([
                    'status' => false,
                    'message' => 'Unauthorized access. Please login.'
                ])->setStatusCode(403);
            }
            return $this->response->setJSON([
                'status' => true,
                'message' => 'Welcome to the user dashboard.',
                'user' => [
                    'id' => $decoded->id,
                    'username' => $decoded->username,
                    'role' => $decoded->role
                ]
            ]);
        }




    public function Admindashboard(){
        $adminCheck = $this->requireAdmin();
        if ($adminCheck !== true) {
            return $adminCheck;
        }

        return $this->response->setJSON(['status' => true,'message'=> 'Welcome to admin dashboard.']);

    }

   

    public function addUser()
    {
        $adminCheck = $this->requireAdmin();   //checking if admin then only can add user
        if ($adminCheck !== true) {
            return $adminCheck; 
        }

        $data = $this->request->getJSON(true);
        
        if ($this->userModel->getUserDataByEmail($data['email'])) {
            return $this->response->setJSON(['status' => false, 'message' => 'User already exists.']);
        }

        $userData = [
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => password_hash($data['password'], PASSWORD_DEFAULT),
        ];

        $this->userModel->saveUserDataInDB($userData);
        return $this->response->setJSON(['status' => true, 'message' => 'User added successfully.']);
    }

    public function updateUser($id)//updating user only by admin
    {
        $adminCheck = $this->requireAdmin();
        if ($adminCheck !== true) {
            return $adminCheck; 
        }
    
        $data = $this->request->getJSON(true);
        
        $existingUser = $this->userModel->findUserById($id);    //getting user by id
        if (!$existingUser) {
            return $this->response->setJSON(['status' => false, 'message' => 'User not found.'])->setStatusCode(404); 
        }
        $data = $this->request->getJSON(true);
        $existingEmail=$this->userModel->getUserDataByEmail($data['email']);
        
        if ($existingEmail && $existingEmail->id != $id) {
            return $this->response->setJSON(['status' => false, 'message' => 'User already exists.']);
        }

        if ($this->userModel->updateUserDataById($id, $data)) {
            return $this->response->setJSON(['status' => true, 'message' => 'User updated successfully.']);
        }
    }
    
    public function deleteUser($id)//deleting only by user
    {
        $adminCheck = $this->requireAdmin();
        if ($adminCheck !== true) {
            return $adminCheck; 
        }

        $existingUser = $this->userModel->findUserById($id);
        if (!$existingUser) {
            return $this->response->setJSON(['status' => false, 'message' => 'User not found.'])->setStatusCode(404);
        }
        $this->userModel->deleteUserDataById($id);
        return $this->response->setJSON(['status' => true, 'message' => 'User deleted successfully.']);
    }
}
