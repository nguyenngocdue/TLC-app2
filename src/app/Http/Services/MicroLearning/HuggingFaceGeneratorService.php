<?php

namespace App\Http\Services\MicroLearning;

use Illuminate\Support\Facades\Http;

class HuggingFaceGeneratorService
{
    function handle(array $text)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env("HUGGINGFACE_API_KEY"),
        ])->post('https://api-inference.huggingface.co/models/sentence-transformers/all-MiniLM-L6-v2', [
            // 'inputs' => "Hello world",
            'inputs' => [
                'source_sentence' => join("", $text),
                'sentences' => $text,
            ],
        ]);

        return $response->json();
    }
}
