<?php

namespace App\Services\HttpClient;

use Symfony\Contracts\HttpClient\ResponseInterface;

interface HttpClientServiceContract
{
    public function post(string $url, array $data, array $headers = []): ResponseInterface;

    public function get(string $url, array $params = []): ResponseInterface;
}