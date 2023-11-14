<?php

namespace backend\helpers;
use Respect\Validation\Validator;

class PayloadValidator{
    public static function validatePayload(?array $payload, Validator $validator){
        try{
            $validator->assert($payload);
            return true;
        }catch(\Respect\Validation\Exceptions\ValidationException $e){
            return $e->getMessage();
        }
    }
}

?>