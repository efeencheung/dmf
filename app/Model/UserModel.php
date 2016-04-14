<?php

namespace Model;

use DMF\Model;

class UserModel extends Model
{
    public function getUserList()
    {
        $sql = 'SELECT * FROM user';
        $stmt = $this->conn->query($sql);
        $users = $stmt->fetchAll();

        return $users;
    }
}
