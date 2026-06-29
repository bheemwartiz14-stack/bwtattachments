<?php
declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Connection\StoreConnectionRequest;
use App\Http\Requests\Admin\Connection\UpdateConnectionRequest;
use App\Models\Connection;
use App\Services\ConnectionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ConnectionController extends Controller
{
    public function __construct(protected ConnectionService $connectionService) {}

    public function index(): View
    {
        $connections = $this->connectionService->paginate(10);

        return view('admin.connections.index', compact('connections'));
    }

    public function create(): View
    {
        return view('admin.connections.form');
    }

    public function store(StoreConnectionRequest $request): RedirectResponse
    {
        $this->connectionService->create($request->validated());

        return redirect()->route('admin.connections.index')->with('success', 'Connection created successfully.');
    }

    public function edit(string $id): View
    {
        $connection = $this->connectionService->findById($id);

        return view('admin.connections.form', compact('connection'));
    }

    public function update(UpdateConnectionRequest $request, string $id): RedirectResponse
    {
        $this->connectionService->update($id, $request->validated());

        return redirect()->route('admin.connections.index')->with('success', 'Connection updated successfully.');
    }

    public function destroy(string $id): RedirectResponse
    {
        $this->connectionService->delete($id);

        return redirect()->route('admin.connections.index')->with('success', 'Connection deleted successfully.');
    }

}
