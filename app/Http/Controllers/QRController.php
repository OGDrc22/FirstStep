<?php

namespace App\Http\Controllers;

use App\Models\QrResults;
use Illuminate\Http\Request;
use Illuminate\Support\Str;


class QRController extends Controller
{
    public function generateQRLink(Request $request)
    {

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

        QrResults::create([
            'token' => $token,
            'payload' => $resultN,
            'expires_at' => now()->addDay()
        ]);

        
        return response()->json([
            'success' => true,
            'url' => config('app.url') . '/view-result?token=' . $token
        ]);
    }

    public function viewResult(Request $request)
    {
        $data = QrResults::where('token', $request->token)->first();


        if ($data->expires_at && now()->gt($data->expires_at)) {
            abort(404);
        }
        if (!$data) {
            abort(400, 'Missing token');
        }

        $results = $data->payload;

        if (!$results) {
            abort(404, 'Invalid or expired data');
        }
        dd($results);

        return view('qr/result_qr', compact('results'));
    }
}