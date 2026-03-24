<?php
require_once __DIR__ . '/vendor/autoload.php';

use GuzzleHttp\Client;

class ApiClient {
    private Client $client;
    
    public function __construct() {
        $this->client = new Client([
            'timeout' => 30,
            'verify' => false  
        ]);
    }
    
    public function request(string $url): array {
        try {
            $response = $this->client->get($url, [
                'headers' => [
                    'User-Agent' => 'PHP Script',
                    'Accept' => 'application/json'
                ]
            ]);
            
            $body = $response->getBody()->getContents();
            return json_decode($body, true);
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }
}