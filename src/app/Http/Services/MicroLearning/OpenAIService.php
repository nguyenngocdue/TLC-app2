<?php

namespace App\Http\Services\MicroLearning;

use GuzzleHttp\Client;

class OpenAIService
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'https://api.openai.com/v1/',
            'headers' => [
                'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),
                'Content-Type'  => 'application/json',
            ],
        ]);
    }

    public function getEmbedding($text)
    {
        $response = $this->client->post('embeddings', [
            'json' => [
                'input' => $text,
                'model' => 'text-embedding-ada-002',
            ],
        ]);

        $data = json_decode($response->getBody(), true);
        return $data['data'][0]['embedding'] ?? null;
    }
}
