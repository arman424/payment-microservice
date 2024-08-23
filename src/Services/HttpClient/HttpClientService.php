<?php

namespace App\Services\HttpClient;

use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

readonly class HttpClientService implements HttpClientServiceContract
{
    public function __construct(
        private HttpClientInterface $client
    ) {
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function post(string $url, array $data, array $headers = []): ResponseInterface
    {
        return $this->client->request('POST', $url, [
            'headers' => $headers,
            'json' => $data,
        ]);
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function get(string $url, array $params = []): ResponseInterface
    {
        return $this->client->request('GET', $url, [
            'query' => $params,
        ]);
    }
}
