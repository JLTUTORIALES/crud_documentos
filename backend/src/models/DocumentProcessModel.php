<?php

namespace backend\models;
use backend\helpers\DBConnection;
use backend\interfaces\Model;

class DocumentProcessModel implements Model{
    public int $id;
    public string $pro_prefix, $pro_name;

    public function __construct(int $id, string $pro_prefix, string $pro_name)
    {
        $this->id = $id;
        $this->pro_prefix = $pro_prefix;
        $this->pro_name = $pro_name;
    }

    public function toArray(): array{
        return[
            "id" => $this->id,
            "pro_prefix" => $this->pro_prefix,
            "pro_name" => $this->pro_name
        ];
    }

    public function  toJSON(): string {
        return json_encode($this->toArray());
    }

    public static function getProcess(int $id) : ?DocumentProcessModel {
        $db = new DBConnection();
        $data = $db->query("SELECT * FROM PRO_PROCESO WHERE PRO_ID = ?", [$id]);

        if (!isset($data[0])){
            return null;
        }

        return new DocumentProcessModel($data[0]['PRO_ID'], $data[0]['PRO_PREFIJO'], $data[0]['PRO_NOMBRE']);
    }

    public static function getAllProcess() : array {
        $db = new DBConnection();
        $data = $db->query("SELECT * FROM PRO_PROCESO");
        return array_map(function($process){
            return new DocumentProcessModel($process['PRO_ID'], $process['PRO_PREFIJO'], $process['PRO_NOMBRE']);
        }, $data);
    }

    
}

?>