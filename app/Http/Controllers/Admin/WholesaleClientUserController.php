<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\WholesaleClient\StoreWholesaleClientUserRequest;
use App\Http\Requests\Admin\WholesaleClient\UpdateWholesaleClientUserRequest;
use App\Services\Admin\WholesaleClientUserServices;
use App\Services\RoleService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class WholesaleClientUserController extends Controller
{
    public function __construct(
        protected WholesaleClientUserServices $wholesaleClientUserServices,
        protected RoleService $roleService,
    ) {}

    public function index(Request $request): View
    {
        $filters = $request->only(['search', 'role', 'status']);
        $users = $this->wholesaleClientUserServices->paginate(10, $filters);
        $roles = $this->roleService->getByNames(['Wholesale Client']);
        return view('admin.wholesale-client-users.index', compact('users', 'roles'));
    }
    public function create(): View
    {
        $roles = $this->roleService->getByNames(['Wholesale Client']);
        return view('admin.wholesale-client-users.form', compact('roles'));
    }

    public function store(StoreWholesaleClientUserRequest $request): RedirectResponse
    {
        $this->wholesaleClientUserServices->create($request->validated());
        return redirect()->route('admin.wholesale-client-users.index')->with('success', 'Wholesale client user created successfully.');
    }

    public function edit(string $id): View
    {
        $user = $this->wholesaleClientUserServices->findById($id);
        $roles = Role::query()->whereIn('name', ['Wholesale Client'])->get();
        $userRole = $user->roles->first()?->name;
        return view('admin.wholesale-client-users.form', compact('user', 'roles', 'userRole'));
    }

    public function update(UpdateWholesaleClientUserRequest $request, string $id): RedirectResponse
    {
        $this->wholesaleClientUserServices->update($id, $request->validated());
        return redirect()->route('admin.wholesale-client-users.index')->with('success', 'Wholesale client user updated successfully.');
    }

    public function destroy(string $id): RedirectResponse
    {
        $this->wholesaleClientUserServices->delete($id);
        return redirect()->route('admin.wholesale-client-users.index')->with('success', 'Wholesale client user deleted successfully.');
    }

}
