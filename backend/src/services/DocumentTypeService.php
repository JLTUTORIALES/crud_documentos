<?php

namespace backend\services;

use backend\interfaces\Service;
use backend\models\DocumentTypeModel;
use Respect\Validation\Validator;

class DocumentTypeService implements Service{
    public function loadEndpoint(string $method, ?array $payload)
    {
        switch($method){
            case 'GET':
                echo json_encode(array_map(function(DocumentTypeModel $model){
                    return $model->toArray();
                }, DocumentTypeModel::getAllTypes()));
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