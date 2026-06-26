<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFileRequest;
use App\Http\Resources\FileResource;
use App\Services\FileService;
use Illuminate\Http\JsonResponse;

class FileController extends Controller
{
    public function __construct(
        protected FileService $fileService,
    ) {}

    public function store(StoreFileRequest $request): JsonResponse
    {
        $data = $this->fileService->storeTemp($request->file('file'));

        return response()->json((new FileResource($data))->toArray($request));
    }

    public function destroy(string $id): JsonResponse
    {
        $deleted = $this->fileService->deleteMedia($id);
        if (!$deleted) {
            return response()->json(['success' => false], 404);
        }

        return response()->json(['success' => true]);
    }
}
