<?php

namespace backend\models;
use backend\helpers\DBConnection;
use backend\interfaces\Model;

class DocumentTypeModel implements Model{
    public int $id;
    public string $tip_prefix, $tip_name;

    public function __construct(int $id, string $pro_prefix, string $pro_name)
    {
        $this->id = $id;
        $this->tip_prefix = $pro_prefix;
        $this->tip_name = $pro_name;
    }

    public function toArray(): array{
        return[
            "id" => $this->id,
            "tip_prefix" => $this->tip_prefix,
            "tip_name" => $this->tip_name
        ];
    }

    public function  toJSON(): string {
        return json_encode($this->toArray());
    }

    public static function getType(int $id) : ?DocumentTypeModel {
        $db = new DBConnection();
        $data = $db->query("SELECT * FROM TIP_TIPO_DOC WHERE TIP_ID = ?", [$id]);

        if (!isset($data[0])){
            return null;
        }

        return new DocumentTypeModel($data[0]['TIP_ID'], $data[0]['TIP_PREFIJO'], $data[0]['TIP_NOMBRE']);
    }

    public static function getAllTypes() : array {
        $db = new DBConnection();
        $data = $db->query("SELECT * FROM TIP_TIPO_DOC");
        return array_map(function($process){
            return new DocumentTypeModel($process['TIP_ID'], $process['TIP_PREFIJO'], $process['TIP_NOMBRE']);
        }, $data);
    }

    
}

?>