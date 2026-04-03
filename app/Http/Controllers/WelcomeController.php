<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ExamResult;
use Illuminate\Support\Facades\DB;

class WelcomeController extends Controller
{
    public function index() {
        $topTracks = ExamResult::selectRaw("JSON_EXTRACT(predicted_track, '$.track') as track_name")
            ->groupBy('track_name')
            ->orderByRaw('COUNT(*) DESC')
            ->limit(2)
            ->pluck('track_name');

        // Clean up the quotes from JSON_EXTRACT (e.g., "Information Technology" -> Information Technology)
        $topTracks = $topTracks->map(fn($item) => trim($item, '"'));
        return view('welcome', compact('topTracks'));
    }
}
