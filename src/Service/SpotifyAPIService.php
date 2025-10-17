<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class SpotifyAPIService
{
    public function __construct(
        private HttpClientInterface $client,
        private CacheInterface $cache,
        private string $spotify_client_id,
        private string $spotify_client_secret
    ) {}

    public function getAccessToken(): string
    {
        $response = $this->client->request(
            'POST', 'https://accounts.spotify.com/api/token', [
                'headers' => [
                    'Authorization' => 'Basic ' . base64_encode($this->spotify_client_id . ':' . $this->spotify_client_secret),
                ],
                'body' => [
                    'grant_type' => 'client_credentials',
                ],
            ]);

        $statusCode = $response->getStatusCode();
        // $statusCode = 200
        $contentType = $response->getHeaders()['content-type'][0];
        // $contentType = 'application/json'
        $content = $response->getContent();
        // $content = '{"id":521583, "name":"symfony-docs", ...}'
        $content = $response->toArray();
        // $content = ['id' => 521583, 'name' => 'symfony-docs', ...]

        return $content['access_token'];
    }

    public function search(string $query): array
    {
        $accessToken = $this->getAccessToken();

        $response = $this->client->request(
            'GET',
            'https://api.spotify.com/v1/search',
            [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                ],
                'query' => [
                    'q' => $query,
                    'type' => 'artist,album',
                    'limit' => 3,
                ],
            ]
        );

        return $response->toArray();
    }
}

?>
