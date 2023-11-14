<?php

namespace backend\services;

use backend\models\AuthModel;
use backend\interfaces\Service;
use backend\models\UserModel;
use Respect\Validation\Validator;

class AuthService implements Service{

    public function loadEndpoint(string $method, ?array $payload)
    {
        if ($method != 'POST'){
            return false;
        }

        $user = UserModel::findUser($payload['user'], $payload['pwd']);
        if ($user == null){
            echo json_encode([
                "status" => "error",
                "message" => "User not found!"
            ]);
            return true;
        }

        $user_data = $user->toArray();
        $auth = new AuthModel();

        $user_data['token'] = $auth->generateToken($user_data);

        echo json_encode([
            "status" => "success",
            "data" => $user_data
        ]);
        return true;
    }

    public function setValidator($method): Validator
    {
        return Validator::key('user', Validator::stringType()->notEmpty())->
            key('pwd', Validator::stringType()->notEmpty());
    }
    
}

?>