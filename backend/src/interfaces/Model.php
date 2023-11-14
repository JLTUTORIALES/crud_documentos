<?php

namespace backend\interfaces;
interface Model{
    public function toArray() : array;
    public function toJSON() : string;
}

?>