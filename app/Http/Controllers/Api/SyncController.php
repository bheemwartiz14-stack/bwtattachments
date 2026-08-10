<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\SyncService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SyncController extends Controller
{
    public function __construct(protected SyncService $sync)
    {
    }

    public function bootstrap(Request $request): JsonResponse
    {
        return response()->json($this->sync->bootstrap($request->user()));
    }

    public function pull(Request $request): JsonResponse
    {
        $data = $request->validate([
            'since' => ['nullable', 'date'],
        ]);

        return response()->json($this->sync->pull($request->user(), $data['since'] ?? null));
    }

    public function push(Request $request): JsonResponse
    {
        $data = $request->validate([
            'operations' => ['required', 'array', 'max:200'],
            'operations.*.operation_uuid' => ['required', 'string', 'max:64'],
            'operations.*.entity' => ['required', 'string'],
            'operations.*.operation' => ['required', 'string'],
            'operations.*.payload' => ['array'],
        ]);

        return response()->json([
            'results' => $this->sync->push($request->user(), $data['operations']),
        ]);
    }

    public function ack(Request $request): JsonResponse
    {
        $data = $request->validate([
            'operation_uuids' => ['required', 'array'],
            'operation_uuids.*' => ['required', 'string'],
        ]);

        return response()->json($this->sync->ack($request->user(), $data['operation_uuids']));
    }
}
