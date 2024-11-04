<?php

namespace App\Http\Services\MicroLearning;

use MongoDB\Client;

class SelectFromMongoDbNetService
{
    function handle()
    {
        // $serverApi = new ServerApi(ServerApi::V1);
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
    }
}
