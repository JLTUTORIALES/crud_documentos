<?php

namespace backend\helpers;

class RestErrorManager{
    public static function error(int $code, $message){
        http_response_code($code);
        return json_encode(array(
            "status" => $code,
            "message" => $message
        ));
    }
}

?>