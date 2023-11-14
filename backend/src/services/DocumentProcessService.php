<?php

namespace backend\services;

use backend\interfaces\Service;
use backend\models\DocumentProcessModel;
use Respect\Validation\Validator;

class DocumentProcessService implements Service{
    public function loadEndpoint(string $method, ?array $payload)
    {
        switch($method){
            case 'GET':
                echo json_encode(array_map(function(DocumentProcessModel $process){
                    return $process->toArray();
                }, DocumentProcessModel::getAllProcess()));
                return true;

        }

        return false;
    }

    public function setValidator($method): Validator
    {
        return new Validator();
    }
}

?>