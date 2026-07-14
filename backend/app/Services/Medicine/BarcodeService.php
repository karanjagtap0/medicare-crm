<?php

namespace App\Services\Medicine;

use App\Models\MedicineBatch;
use Picqer\Barcode\BarcodeGeneratorPNG;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;

class BarcodeService
{
    public function generateBarcode(int $batchId)
    {
        $batch = MedicineBatch::findOrFail($batchId);
        
        if (!$batch->barcode) {
            $barcode = 'B' . str_pad((string)$batch->id, 5, '0', STR_PAD_LEFT) . strtoupper(Str::random(6));
            
            while (MedicineBatch::where('barcode', $barcode)->exists()) {
                $barcode = 'B' . str_pad((string)$batch->id, 5, '0', STR_PAD_LEFT) . strtoupper(Str::random(6));
            }
            
            $batch->update([
                'barcode' => $barcode,
                'barcode_type' => 'CODE128'
            ]);
        }
        
        return $batch;
    }

    public function getBarcodeDetails(int $batchId)
    {
        return MedicineBatch::with('medicine')->findOrFail($batchId);
    }

    public function searchByBarcode(string $barcode)
    {
        return MedicineBatch::with('medicine')->where('barcode', $barcode)->firstOrFail();
    }

    public function getBarcodeImageBase64(MedicineBatch $batch)
    {
        if (!$batch->barcode) {
            throw new \Exception('No barcode generated for this batch.');
        }

        $generator = new BarcodeGeneratorPNG();
        $image = $generator->getBarcode($batch->barcode, $generator::TYPE_CODE_128, 3, 50);
        
        return base64_encode($image);
    }

    public function generateBarcodePdf(MedicineBatch $batch)
    {
        $base64Image = $this->getBarcodeImageBase64($batch);
        
        $html = '
        <html>
            <head>
                <title>Barcode - ' . $batch->batch_number . '</title>
                <style>
                    body { text-align: center; padding-top: 50px; font-family: sans-serif; }
                    .barcode { margin-bottom: 10px; }
                    .number { font-size: 24px; font-weight: bold; letter-spacing: 2px; }
                </style>
            </head>
            <body>
                <img src="data:image/png;base64,' . $base64Image . '" class="barcode" />
                <div class="number">' . $batch->barcode . '</div>
                <div>Batch: ' . $batch->batch_number . '</div>
            </body>
        </html>
        ';

        return Pdf::loadHTML($html);
    }
}
