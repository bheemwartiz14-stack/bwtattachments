<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class TempFileController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'file' => ['required', 'file', 'max:10240'],
        ]);

        $file = $request->file('file');
        $token = Str::random(32);
        $originalName = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
        $path = $file->storeAs('temp/' . $token, $originalName, 'public');
        $mimeType = $file->getMimeType();

        return response()->json([
            'token' => $token,
            'name' => $originalName,
            'size' => $file->getSize(),
            'url' => Storage::disk('public')->url($path),
            'mime_type' => $mimeType,
            'extension' => $extension,
        ]);
    }
}
