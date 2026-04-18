<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;


class QRController extends Controller
{
    public function generateQRLink(Request $request)
    {
        // try {
        //     $imageData = $request->input('image');
        //     $image = str_replace('data:image/png;base64,', '', $imageData);
        //     $image = str_replace(' ', '+', $image);
        //     $imageBinary = base64_decode($image);

        //     // Generate a unique filename so results don't overwrite each other
        //     $fileName = 'results/Report_' . time() . '_' . uniqid() . '.png';

        //     // Save to storage/app/public/results
        //     \Storage::disk('public')->put($fileName, $imageBinary);
        //     $justTheName = basename($fileName);

        //     return response()->json([
        //         'success' => true,
        //         'url' => route('download-result', ['file' => $justTheName]) // The direct link for the QR scan
        //     ]);
        // } catch (\Exception $e) {
        //     return response()->json(['success' => false], 500);
        // }
        // try {
        //     $imageData = $request->input('image');

        //     return response()->json([
        //         'success' => true,
        //         'url' => route('download-result', ['file' => $justTheName]) // The direct link for the QR scan
        //     ]);
        // } catch (\Exception $e) {
        //     return response()->json(['success' => false], 500);
        // }

        $resultN = session('resultN');

        // Remove the key
        unset($resultN['questionsData']);
        unset($resultN['acc_per_category']);
        unset($resultN['duration_per_category']);
        unset($resultN['model_accuracy']);
        // unset($resultN['note']);
        unset($resultN['detailedCompetencyLevels']);

        // $bytes = strlen(serialize($resultN)); 
        // if ($bytes >= 1048576) {
        //     dd(number_format($bytes / 1048576, 2) . ' MB', $resultN);
        // } else {
        //     dd(number_format($bytes / 1024, 2) . ' KB', $resultN);
        // }

        $token = Str::random(10);

        cache()->put('result_' . $token, $resultN, now()->addMinutes(30));

        return response()->json([
            'success' => true,
            'url' => route('view-result', ['token' => $token])
        ]);
    }

    public function viewResult(Request $request)
    {
        $token = $request->query('token');

        if (!$token) {
            abort(400, 'Missing token');
        }

        $results = cache()->get('result_' . $token);

        if (!$results) {
            abort(404, 'Invalid or expired data');
        }

        // dd($results);

        return view('qr/result_qr', compact('results'));
    }
}