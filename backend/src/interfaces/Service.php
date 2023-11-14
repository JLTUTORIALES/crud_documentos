<?php

namespace backend\interfaces;
use Respect\Validation\Validator;

interface Service{
    public function loadEndpoint(string $method, ?array $payload);
    public function setValidator(string $method) : Validator;
}

?>