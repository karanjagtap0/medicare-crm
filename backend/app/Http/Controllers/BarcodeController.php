<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\Medicine\BarcodeService;
use Illuminate\Http\Request;

class BarcodeController extends Controller
{
    public function __construct(private BarcodeService $barcodeService)
    {
    }

    public function generate(Request $request)
    {
        if (!$request->user()?->can('medicine.edit')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'batch_id' => 'required|integer|exists:medicine_batches,id'
        ]);

        $batch = $this->barcodeService->generateBarcode($request->batch_id);

        return response()->json([
            'success' => true,
            'message' => 'Barcode generated successfully.',
            'data' => $batch,
        ], 200);
    }

    public function show(Request $request, $id)
    {
        if (!$request->user()?->can('medicine.view')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $batch = $this->barcodeService->getBarcodeDetails($id);

        return response()->json([
            'success' => true,
            'message' => 'Barcode details retrieved.',
            'data' => $batch,
        ], 200);
    }

    public function search(Request $request)
    {
        if (!$request->user()?->can('medicine.view')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'barcode' => 'required|string'
        ]);

        try {
            $batch = $this->barcodeService->searchByBarcode($request->barcode);
            return response()->json([
                'success' => true,
                'data' => $batch,
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Barcode not found.'], 404);
        }
    }

    public function print(Request $request, $id)
    {
        if (!$request->user()?->can('medicine.view')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        try {
            $batch = $this->barcodeService->getBarcodeDetails($id);
            $base64 = $this->barcodeService->getBarcodeImageBase64($batch);
            
            $html = '
            <html>
                <body onload="window.print()">
                    <div style="text-align: center; margin-top: 50px;">
                        <img src="data:image/png;base64,' . $base64 . '" />
                        <div style="font-size: 24px; font-weight: bold; font-family: sans-serif; letter-spacing: 2px;">' . $batch->barcode . '</div>
                        <div style="font-size: 16px; font-family: sans-serif; margin-top: 5px;">Batch: ' . $batch->batch_number . '</div>
                    </div>
                </body>
            </html>';
            
            return response($html)->header('Content-Type', 'text/html');
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }

    public function download(Request $request, $id)
    {
        if (!$request->user()?->can('medicine.view')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        try {
            $batch = $this->barcodeService->getBarcodeDetails($id);
            $pdf = $this->barcodeService->generateBarcodePdf($batch);
            
            return $pdf->download('barcode-' . $batch->barcode . '.pdf');
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }
}
