<?php

namespace backend\services;

use backend\helpers\RestErrorManager;
use backend\interfaces\Service;
use backend\models\DocumentModel;
use backend\models\DocumentProcessModel;
use backend\models\DocumentTypeModel;
use Respect\Validation\Validator;

class DocumentService implements Service{

    public function loadEndpoint(string $method, ?array $payload){
        switch($method){
            case 'GET':
                echo json_encode($this->getAllParsedDocuments());
                return true;
            case 'POST':

                if (!DocumentModel::checkTypeAndProcess($payload['id_type'], $payload['id_process'])){
                    return true;
                }
                $document_code = DocumentModel::createDocumentCode($payload['id_type'], $payload['id_process']);
                $document = new DocumentModel(
                    -1,
                    $payload['id_type'],
                    $payload['id_process'],
                    $payload['name'],
                    $document_code,
                    $payload['content'],
                );

                if (DocumentModel::saveDocument($document)){
                    echo json_encode($this->getAllParsedDocuments());
                }

                return true;

            case 'PUT':
                if (!DocumentModel::checkTypeAndProcess($payload['id_type'], $payload['id_process'])){
                    return true;
                }

                $document = DocumentModel::getDocument($payload['id']);
                if ($document == null){
                    RestErrorManager::error(400, "Document with ID: {$payload['id']} not exists");
                    return true;
                }
                
                $document->doc_name = $payload['name'];
                $document->doc_content = $payload['content'];
                $document->doc_id_type = $payload['id_type'];
                $document->doc_id_process = $payload['id_process'];

                if (DocumentModel::updateDocument($document)){
                    echo DocumentModel::getDocument($payload['id'])->toJSON();
                }
                return true;
            
            case 'DELETE':
                if (DocumentModel::getDocument($payload['id']) == null){
                    echo RestErrorManager::error(400, "Document with ID: {$payload['id']} not exists");
                    return true;
                }

                if (DocumentModel::deleteDocument($payload['id'])){
                    echo json_encode($this->getAllParsedDocuments());
                }

                return true;
        }

        return false;
    }

    private function getAllParsedDocuments(){
        return array_map(function(DocumentModel $document){
            return $document->toArray();
        }, DocumentModel::getAllDocuments());
    }

    public function setValidator(string $method) : Validator{

        $default_validator = Validator::key('name', Validator::stringType()->notEmpty())->
        key('content', Validator::stringType()->notEmpty())->
        key('id_type', Validator::intType()->notEmpty())->
        key('id_process', Validator::intType()->notEmpty());

        switch($method){
            case 'POST':
                return $default_validator;

            case 'PUT':
                return $default_validator->key('id', Validator::intType()->notEmpty());

            case 'DELETE':
                return Validator::key('id', Validator::intType()->notEmpty());

        }
        return new Validator();
    }
}