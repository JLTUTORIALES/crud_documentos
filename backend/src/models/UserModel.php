<?php

namespace backend\models;
use backend\helpers\DBConnection;
use backend\interfaces\Model;

class UserModel implements Model{
    private int $id;
    private string $name, $username;
    public function __construct($id, $name, $username)
    {
        $this->id = $id;
        $this->name = $name;
        $this->username = $username;
    }

    public function toArray() : array {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "username" => $this->username
        ];
    }

    public function toJSON() : string {
        return json_encode($this->toArray());
    }

    public static function findUser($user, $pwd){
        $db = new DBConnection();
        $result = $db->query("SELECT * FROM USERS WHERE USER_NAME = ?", [$user]);
        
        if(count($result) > 0){
            $result = $result[0];
            if(password_verify($pwd, $result['PASSWORD'])){
                return new UserModel($result['ID_USER'], $result['NAME'], $result['USER_NAME']);
            }
        }
        return null;
    }
}

?>