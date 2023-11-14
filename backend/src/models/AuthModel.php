<?php

namespace backend\models;

use backend\helpers\ENVManager;
use backend\helpers\RestErrorManager;
use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AuthModel
{
    private string $secretKey;
    public function __construct()
    {
        $this->secretKey = ENVManager::GetENV('JWT_SECRET_KEY');
    }

    public function generateToken(array $payload)
    {
        $payload = array_merge($payload, [
            'iat' => time(),
            'exp' => time() + 3600
        ]);
        return JWT::encode($payload, $this->secretKey, 'HS256');
    }

    public static function validateToken()
    {
        $headers = getallheaders();
        $token = isset($headers['Authorization']) ? $headers['Authorization'] : '';
        $token = str_replace('Bearer ', '', $token);
        $auth = new AuthModel();
        try{
            return JWT::decode($token, new Key($auth->getSecretKey(), 'HS256'));
        }catch(Exception $e){
            echo RestErrorManager::error(403, "Invalid session: {$e->getMessage()}");
            exit;
        }
    }

    public function getSecretKey() { return $this->secretKey; }
}

?>