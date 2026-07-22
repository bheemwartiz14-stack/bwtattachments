<?php
declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\WholesaleClient\StoreWholesaleClientUserRequest;
use App\Http\Requests\Admin\WholesaleClient\UpdateWholesaleClientUserRequest;
use App\Services\Admin\WholesaleClientUserServices;
use App\Services\RoleService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

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
        return view('pages.private.admin.wholesale-client-users.index', compact('users'));
    }
    public function create(): View
    {
        $roles = $this->roleService->getByNames(['Wholesale']);
        return view('pages.private.admin.wholesale-client-users.form', compact('roles'));
    }

    public function store(StoreWholesaleClientUserRequest $request): RedirectResponse
    {
        $this->wholesaleClientUserServices->create($request->validated());
        return redirect()->route('admin.wholesale-client-users.index')->with('success', 'Wholesale user created successfully.');
    }

    public function show(string $id): View
    {
        $user = $this->wholesaleClientUserServices->findById($id);
        $user->load(['userMeta', 'userMargin', 'quotations' => fn ($q) => $q->latest()->take(10)]);
        return view('pages.private.admin.wholesale-client-users.show', compact('user'));
    }

    public function edit(string $id): View
    {
        $user = $this->wholesaleClientUserServices->findById($id);
        $userRole = $user->roles->first()?->name;
        $roles = $this->roleService->getByNames(['Wholesale']);
        return view('pages.private.admin.wholesale-client-users.form', compact('user', 'roles', 'userRole'));
    }

    public function update(UpdateWholesaleClientUserRequest $request, string $id): RedirectResponse
    {
        $this->wholesaleClientUserServices->update($id, $request->validated());
        return redirect()->route('admin.wholesale-client-users.index')->with('success', 'Wholesale user updated successfully.');
    }

    public function destroy(string $id): RedirectResponse
    {
        $this->wholesaleClientUserServices->delete($id);
        return redirect()->route('admin.wholesale-client-users.index')->with('success', 'Wholesale user deleted successfully.');
    }

}
