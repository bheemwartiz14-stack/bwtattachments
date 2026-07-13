<?php
declare(strict_types=1);

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Services\RoleService;
use App\Http\Requests\Client\Retailer\StoreRetailerClientUserRequest;
use App\Http\Requests\Client\Retailer\UpdateRetailerClientUserRequest;
use App\Services\Client\RetailerClientUserService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;

class ResellerClientUserController extends Controller
{
     public function __construct(
        protected RoleService $roleService,
        protected RetailerClientUserService $retailerClientUserService,
    ) {}
    /**
     * Display a listing of the resource.
     */
        public function index(Request $request): View
    {
        $filters = $request->only(['search', 'role', 'status']);
        $users = $this->retailerClientUserService->paginate(10, $filters);
        $roles = $this->roleService->getByNames(['Reseller']);
        return view('pages.private.client.reseller-users.index', compact(
            'users',
            'roles',
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = $this->roleService->getByNames(['Reseller']);
        return view('pages.private.client.reseller-users.form', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRetailerClientUserRequest $request)
    {
        $data = $request->validated();
        $this->retailerClientUserService->create($data);
         return redirect()
            ->route('client.reseller-users.index')
            ->with('success', 'Retailer client user created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = $this->retailerClientUserService->findById($id);
        $user->load(['userMeta', 'userMargin', 'quotations' => fn ($q) => $q->latest()->take(10)]);
        return view('pages.private.client.reseller-users.show', compact('user'));
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): View
    {
        $user = $this->retailerClientUserService->findById($id);
        $userRole = $user->roles->first()?->name ?? 'Retailer';
        $meta = $user->userMeta?->metadata ?? [];
        return view('pages.private.client.reseller-users.form', compact('user', 'userRole', 'meta'));
    }

    public function update(UpdateRetailerClientUserRequest $request, string $id): RedirectResponse
    {
        $this->retailerClientUserService->update($id, $request->validated());
        return redirect()->route('client.reseller-users.index')->with('success', 'Retailer client user updated successfully.');
    }

    public function destroy(string $id): RedirectResponse
    {
        $this->retailerClientUserService->delete($id);
        return redirect()->route('client.reseller-users.index')->with('success', 'Retailer client user deleted successfully.');
    }
}
