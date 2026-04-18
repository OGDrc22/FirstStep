<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ExamResult;
use Illuminate\Support\Facades\DB;

class WelcomeController extends Controller
{
    public function index() {
        // dd([
        //     'env_app_url' => env('APP_URL'),
        //     'config_app_url' => config('app.url'),
        // ]);
        $total = ExamResult::count();

        $tracks = ExamResult::selectRaw("JSON_UNQUOTE(JSON_EXTRACT(predicted_track, '$.track')) as track_name, COUNT(*) as total_count")
            ->groupBy('track_name')
            ->orderByDesc('total_count')
            ->take(2)
            ->get();
        
        $topTracks = $tracks->map(function ($item) use ($total) {
            return [
                'track' => $item->track_name,
                'count' => $item->total_count,
                'percentage' => round(($item->total_count / $total) * 100, 2)
            ];
        });
        
        return view('welcome', compact('topTracks'));
    }
}
