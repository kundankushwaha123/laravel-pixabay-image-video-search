<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PixabayController extends Controller
{
    public function index()
    {
        return view('pixabay.index');
    }

    public function search(Request $request)
    {
        $request->validate([
            'query' => 'required|string|max:255',
            'type' => 'required|in:image,video',
        ]);

        $query = $request->query('query');
        $type  = $request->query('type');

        $url = $type === 'video'? config('services.pixabay.video_url'): config('services.pixabay.base_url');

        $response = Http::get($url, [
            'key' => config('services.pixabay.key'),
            'q' => $query,
            'per_page' => 12,
        ]);

        return response()->json($response->json());
    }
}
