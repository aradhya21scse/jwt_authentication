<?php

namespace App\Controllers;
use App\Models\UsersModel;

class UserValidation extends BaseController
{
    public function validateReg($data)
    {
        if (empty($data['username'])) {
            return [
                'status' => false,
                'message' => 'Username is required.'
            ];
        }

        if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            return [
                'status' => false,
                'message' => 'A valid email is required.'
            ];
        }

        if (empty($data['password']) || strlen($data['password']) < 6) {
            return [
                'status' => false,
                'message' => 'Password must be at least 6 characters'
            ];
        }

        return ['status' => true];
    }

    public function validateLogin($data)
    {
        if (empty($data['email'])) {
            return [
                'status' => false,
                'message' => 'Email is required.'
            ];
        }

        if (empty($data['password'])) {
            return [
                'status' => false,
                'message' => 'Password is required.'
            ];
        }

        return ['status' => true];
    }
}
