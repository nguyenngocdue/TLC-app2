<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Smalot\PdfParser\Parser;

class WelcomeFortunePdfExtractController extends Controller
{

    function getType()
    {
        return "dashboard";
    }

    private function extractBySmalot($index, $filePath)
    {
        $name = md5($filePath);
        if (DB::table('pdfs')->where('name', $name)->exists()) {
            dump($index . ' exists=> ' . $filePath);
            return;
        }
        dump("Processing=> $filePath");
        $parser = new Parser();
        try {
            $pdf = $parser->parseFile(storage_path('app/public/' . $filePath));
            $text = $pdf->getText();
        } catch (\Exception $e) {
            dump($e->getCode() . ' error=> ' . $filePath . ' ' . $e->getMessage());
            return;
        }
        try {
            DB::table('pdfs')->insert([
                'name' => $name,
                'description' => $filePath,
                'content' => $text,
                'owner_id' => 1,
            ]);
        } catch (\Exception $e) {
            dump($e->getCode() . ' duplicated name=> ' . $filePath);
        }
        // dump($text);
    }

    private function loadFiles()
    {
        $allFiles = Storage::disk('public')->allFiles();
        $pdfFiles = array_filter($allFiles, function ($file) {
            return strtolower(substr($file, -4)) === '.pdf';
        });

        return $pdfFiles;
    }

    private function loadPdfIntoDb()
    {
        $files = $this->loadFiles();
        dump(sizeof($files));
        // dump($files);
        foreach ($files as $index => $file) {
            $this->extractBySmalot($index, $file);
        }
    }

    public function index(Request $request)
    {
        // $this->loadPdfIntoDb();
        return view("welcome-fortune", []);
    }
}
