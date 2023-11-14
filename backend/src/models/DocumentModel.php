<?php

namespace backend\models;
use backend\helpers\DBConnection;
use backend\helpers\RestErrorManager;
use backend\interfaces\Model;

class DocumentModel implements Model{

    public int $id, $doc_id_type, $doc_id_process;
    public string $doc_name, $doc_code, $doc_content;

    public function __construct($id, $doc_id_type, $doc_id_process, $doc_name, $doc_code, $doc_content)
    {
        $this->id = $id;
        $this->doc_id_type = $doc_id_type;
        $this->doc_id_process = $doc_id_process;
        $this->doc_name = $doc_name;
        $this->doc_code = $doc_code;
        $this->doc_content = $doc_content;
    }

    public function toArray(): array
    {
        return [
            "id"           => $this->id,
            "type"     => DocumentTypeModel::getType($this->doc_id_type)->toArray(),
            "process"  => DocumentProcessModel::getProcess($this->doc_id_process)->toArray(),
            "name"     => $this->doc_name,
            "code"     => $this->doc_code,
            "content"  => $this->doc_content,
        ];
    }

    public function toJSON(): string
    {
        return json_encode($this->toArray());
    }

    public static function getDocument(int $id){
        $db = new DBConnection();
        $data = $db->query("SELECT * FROM DOC_DOCUMENTO WHERE DOC_ID = ? AND DELETED = 0", [$id]);

        if (!isset($data[0])){
            return null;
        }

        return new DocumentModel(
            $data[0]['DOC_ID'],
            $data[0]['DOC_ID_TIPO'],
            $data[0]['DOC_ID_PROCESO'],
            $data[0]['DOC_NOMBRE'],
            $data[0]['DOC_CODIGO'],
            $data[0]['DOC_CONTENIDO'],
        );
    }

    public static function getAllDocuments(){
        $db = new DBConnection();
        $data = $db->query('SELECT * FROM DOC_DOCUMENTO WHERE DELETED = 0');
        return array_map(function($document){
            return new DocumentModel(
                $document['DOC_ID'],
                $document['DOC_ID_TIPO'],
                $document['DOC_ID_PROCESO'],
                $document['DOC_NOMBRE'],
                $document['DOC_CODIGO'],
                $document['DOC_CONTENIDO'],
            );
        }, $data);
    }

    public static function saveDocument(DocumentModel $document){
        $db = new DBConnection();

        return $db->query('INSERT INTO DOC_DOCUMENTO VALUES (NULL, ?, ?, ?, ?, ?, 0)', [
            $document->doc_name,
            $document->doc_code,
            $document->doc_content,
            $document->doc_id_type,
            $document->doc_id_process,
        ]);
    }

    public static function updateDocument(DocumentModel $document){
        $db = new DBConnection();
        $originalDocument = self::getDocument($document->id);

        if (
            $document->doc_id_type != $originalDocument->doc_id_type ||
            $document->doc_id_process != $originalDocument->doc_id_process
        ){
            $document->doc_code = self::createDocumentCode($document->doc_id_type, $document->doc_id_process);
        }

        return $db->query('UPDATE
        DOC_DOCUMENTO SET DOC_NOMBRE = ?, DOC_CODIGO = ?, DOC_CONTENIDO = ?, DOC_ID_TIPO = ?, DOC_ID_PROCESO = ?
        WHERE DOC_ID = ?', [
            $document->doc_name,
            $document->doc_code,
            $document->doc_content,
            $document->doc_id_type,
            $document->doc_id_process,
            $originalDocument->id
        ]);
    }

    public static function deleteDocument(int $doc_id){
        $db = new DBConnection();
        return $db->query('UPDATE DOC_DOCUMENTO SET DELETED = 1 WHERE DOC_ID = ?', [$doc_id]);
    }

    public static function checkTypeAndProcess($id_type, $id_process){
        if (DocumentTypeModel::getType($id_type) == null){
            echo RestErrorManager::error(400, "Invalid document type ID: $id_type");
            return false;
        }
        if (DocumentProcessModel::getProcess($id_process) == null){
            echo RestErrorManager::error(400, "Invalid document process ID: $id_process");
            return false;
        }

        return true;
    }



    public static function createDocumentCode(int $id_type, int $id_process){
        $type = DocumentTypeModel::getType($id_type);
        $process = DocumentProcessModel::getProcess($id_process);

        $prefix = "{$type->tip_prefix}-{$process->pro_prefix}";

        $db = new DBConnection();
        $index = $db->query('SELECT COUNT(*) + 1 AS CONTEO FROM DOC_DOCUMENTO WHERE DOC_CODIGO LIKE ?', [
            "%$prefix%"
        ])[0]['CONTEO'];

        return "$prefix-$index";
    }
}