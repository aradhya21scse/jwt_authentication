<?php
namespace App\Controllers;

class MessageController extends BaseController
{
    public function displayMessage($status, $message,$data=[])
    {
        return response()->setJSON([
            'status' => $status,
            'message' => $message,
            'data'=> $data
        ]);
    }
}
