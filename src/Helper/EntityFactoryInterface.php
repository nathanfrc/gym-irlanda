<?php

namespace App\Helper;

interface EntityFactoryInterface
{
    public function createEntity(string $json, string $method, string $user);
}
