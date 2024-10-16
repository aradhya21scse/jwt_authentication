<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'user';
    protected $primaryKey = 'id';
    protected $allowedFields = ['username', 'email', 'password', 'role'];

    public function saveUserDataInDB($data)
    {
        $sql= "Insert into {$this->table}  (username,email,password) values ('{$data['username']}','{$data['email']}','{$data['password']}')";

        $this->db->query($sql);
    }
    public function getUserDataByEmail($email){
        $sql= "Select * from {$this->table} where email = ?";
        $query = $this->db->query($sql,[$email]);

        return  $query->getRow();

    }
    public function findUserById($id){
        $sql= "Select * from {$this->table} where id =?";
        $query = $this->db->query($sql,[$id]);
        return  $query->getRow();

    }

    public function updateUserDataById($id, $data) {
        $sql = "UPDATE {$this->table} SET username = '$data[username]', email ='$data[email]', password='$data[password]'  WHERE id = '$id'";
    
        $query = $this->db->query($sql);
        return $query ? true : false;
    }

    public function deleteUserDataById($id){
        $sql="DELETE FROM {$this->table} WHERE id = '$id'";

        $query=$this->db->query($sql);
        return $query ? true : false;

    }
}
