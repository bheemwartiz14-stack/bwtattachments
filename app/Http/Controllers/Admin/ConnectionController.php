<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreConnectionRequest;
use App\Http\Requests\UpdateConnectionRequest;
use App\Models\Connection;
use App\Services\ConnectionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ConnectionController extends Controller
{
    public function __construct(protected ConnectionService $connectionService) {}

    public function index(): View
    {
        $this->authorize('viewAny', Connection::class);

        $connections = $this->connectionService->paginate(10);

        return view('admin.connections.index', compact('connections'));
    }

    public function create(): View
    {
        $this->authorize('create', Connection::class);

        return view('admin.connections.form');
    }

    public function store(StoreConnectionRequest $request): RedirectResponse
    {
        $this->authorize('create', Connection::class);

        $this->connectionService->create($request->validated());

        return redirect()->route('admin.connections.index')->with('success', 'Connection created successfully.');
    }

    public function edit(int $id): View
    {
        $this->authorize('update', Connection::class);

        $connection = $this->connectionService->findById($id);

        return view('admin.connections.form', compact('connection'));
    }

    public function update(UpdateConnectionRequest $request, int $id): RedirectResponse
    {
        $this->authorize('update', Connection::class);

        $this->connectionService->update($id, $request->validated());

        return redirect()->route('admin.connections.index')->with('success', 'Connection updated successfully.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $this->authorize('delete', Connection::class);

        $this->connectionService->delete($id);

        return redirect()->route('admin.connections.index')->with('success', 'Connection deleted successfully.');
    }

    public function restore(int $id): RedirectResponse
    {
        $this->authorize('restore', Connection::class);

        $this->connectionService->restore($id);

        return redirect()->route('admin.connections.index')->with('success', 'Connection restored successfully.');
    }
}
