<?php

namespace App\Http\Services;

use App\BigThink\Math;
use App\Models\Pdf_file;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Spatie\PdfToText\Pdf;

class PdfToTextService
{
    function __construct() {}

    function getType()
    {
        return "dashboard";
    }

    public function handle($path = 'public/pdfs')
    {
        $pdf_files = Pdf_file::query()->get();
        $existingFingerPrints = $pdf_files->pluck('finger_print')->toArray();
        Log::info("Existing Fingerprints: " . json_encode($existingFingerPrints));

        $files = Storage::files($path);

        foreach ($files as $file) {
            $text = (new Pdf())
                ->setPdf(storage_path('app/' . $file))
                ->text();
            // Step 1: Clean up paragraphs
            $cleanedText = preg_replace("/([a-z])-\n([a-z])/i", "$1$2", $text); // Join hyphenated words
            $cleanedText = preg_replace("/\n(?!\n)/", " ", $cleanedText);       // Replace single line breaks with spaces
            $cleanedText = preg_replace("/\s{2,}/", " ", $cleanedText);         // Remove extra spaces

            $first_page = substr($cleanedText, 0, 1024);
            // dump($first_page);

            $fingerPrint = Math::createDiginetFingerprint([$first_page]);
            // dump($file . " - " . $fingerPrint);
            if (in_array($fingerPrint, $existingFingerPrints)) {
                Log::info("Skipped: {$file}");
                continue;
            }

            $row = [
                'name' => $file,
                'content' => $cleanedText,
                'finger_print' => $fingerPrint,
                'owner_id' => 1,
            ];

            $inserted = Pdf_file::create($row);
            $existingFingerPrints[] = $fingerPrint;
            Log::info("Inserted: {$inserted->id}");
        }
    }
}
