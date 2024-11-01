<?php

namespace App\Http\Controllers;

use App\Http\Services\OpenAIService;
use App\Http\Services\PdfToTextService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use MongoDB\Client;
use Illuminate\Support\Facades\Http;

class WelcomeFortuneController extends Controller
{
    function __construct(
        private OpenAIService $openAIService,
        private PdfToTextService $pdfToTextService,
    ) {}

    function getType()
    {
        return "dashboard";
    }

    function generateEmbedding($text)
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

    public function index(Request $request)
    {
        // $serverApi = new ServerApi(ServerApi::V1);

        // // Create a new client and connect to the server
        $client = new Client(
            env("MONGODB_DSN"),
            [],
            // ['serverApi' => $serverApi]
        );
        try {
            // Send a ping to confirm a successful connection
            $client->selectDatabase('admin')->command(['ping' => 1]);
            echo "Pinged your deployment. You successfully connected to MongoDB!\n";

            // List all databases
            $databases = $client->listDatabases();

            echo "<ul>";
            foreach ($databases as $database) {
                echo "<li>Database: " . $database->getName() . "</li>";
                echo "<ul>";
                $db = $client->selectDatabase($database->getName());

                // List all collections (tables) in the selected database
                $collections = $db->listCollections();

                foreach ($collections as $collection) {
                    echo "<li>Collection: " . $collection->getName() . "</li>";
                }
                echo "</ul>";
            }
            echo "</ul>";
        } catch (\Exception $e) {
            printf($e->getMessage());
        }


        $vector = $this->generateEmbedding([
            "Hello World",
            "To day is a very nice day, but I cant see it."
        ]);
        Log::info($vector);


        // $vector = $this->openAIService->getEmbedding("Hello World");
        // Log::info($vector);

        // return view("welcome-fortune", [
        //     'columns' => $columns,
        //     'dataSource' => $tables,
        // ]);
    }
}
