<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Users\StoreUserRequest;
use App\Http\Requests\Admin\Users\UpdateUserRequest;
use App\Models\Company;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function __construct(protected UserService $userService) {}

    public function index(Request $request): View
    {
        $this->authorize('viewAny', User::class);

        $filters = $request->only(['search', 'role', 'status']);
        $users = $this->userService->paginate(10, $filters);
        $roles = Role::all();

        return view('admin.users.index', compact('users', 'roles'));
    }

    public function create(): View
    {
        $this->authorize('create', User::class);
        $roles = Role::all();
        $companies = Company::all();
        return view('admin.users.form', compact('roles', 'companies'));
    }

    /**
     * ✅ DEBUG VERSION (use this first)
     */
    public function store(StoreUserRequest $request): RedirectResponse
    {
        $data = $request->all();
         $this->userService->create($data);
        return redirect()->route('admin.users.index') ->with('success', 'User created successfully.');
    }

    public function edit(string $id): View
    {
        $this->authorize('update', User::class);

        $user = $this->userService->findById($id);
        $roles = Role::all();
        $companies = Company::all();
        $userRole = $user->roles->first()?->name;
        return view('admin.users.form', compact('user', 'roles', 'companies', 'userRole'));
    }

    public function update(UpdateUserRequest $request, string $id): RedirectResponse
    {
        $this->userService->update($id, $request->validated());
        return redirect()->route('admin.users.index') ->with('success', 'User updated successfully.');
    }
    public function destroy(string $id): RedirectResponse
    {
        $this->authorize('delete', User::class);
        $this->userService->delete($id);
        return redirect()->route('admin.users.index') ->with('success', 'User deleted successfully.');
    }

    public function restore(string $id): RedirectResponse
    {
        $this->userService->restore($id);

        return redirect()->route('admin.users.index')
            ->with('success', 'User restored successfully.');
    }
}