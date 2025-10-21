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

       return $this->cache->get('spotify_access_token', function (ItemInterface $item): string {
            $item->expiresAfter(3500); // Slightly less than 1 hour to prevent expiry issues

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
       });
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

        $content = $response->toArray();

        $artists = array_map(fn($artist) => [
            'type' => 'artist',
            'spotify_id' => $artist['id'],
            'name' => $artist['name'],
            'image' => $artist['images'][0]['url'] ?? null,
        ], $content['artists']['items'] ?? []);

        $albums = array_map(fn($album) => [
            'type' => 'album',
            'spotify_id' => $album['id'],
            'name' => $album['name'],   
            'image' => $album['images'][0]['url'] ?? null,
            'artists' => implode(', ', array_map(fn($a) => $a['name'], $album['artists'] ?? [])),
            'release_date' => $album['release_date'] ?? null,
        ], $content['albums']['items'] ?? []);

        return array_merge($artists, $albums);
    }
}

?>
