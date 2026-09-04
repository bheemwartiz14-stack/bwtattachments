<?php
declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\WholesaleClient\StoreWholesaleClientUserRequest;
use App\Http\Requests\Admin\WholesaleClient\UpdateWholesaleClientUserRequest;
use App\Services\Admin\WholesaleClientUserServices;
use App\Services\RoleService;
use App\Services\VatRateService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WholesaleClientUserController extends Controller
{
    public function __construct(
        protected WholesaleClientUserServices $wholesaleClientUserServices,
        protected RoleService $roleService,
        protected VatRateService $vatRateService

    ) {}

    public function index(Request $request): View
    {
        $filters = $request->only(['search', 'role', 'status']);
        $users = $this->wholesaleClientUserServices->paginate(1000, $filters);
        return view('pages.private.admin.wholesale-client-users.index', compact('users'));
    }
    public function create(): View
    {
        $roles = $this->roleService->getByNames(['Wholesale']);
        $vatcountries = $this->vatRateService->options();
        return view('pages.private.admin.wholesale-client-users.form', compact('roles','vatcountries'));
    }
    public function store(StoreWholesaleClientUserRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $this->wholesaleClientUserServices->create($data);
        return redirect()->route('admin.wholeseller.index')->with('success', 'Wholesaler  created successfully.');
    }
    public function show(string $id): View
    {
        $user = $this->wholesaleClientUserServices->findById($id);
        $user->load(['userMeta', 'userMargin', 'quotations' => fn ($q) => $q->latest()->take(10)
        ,'orders' => fn ($q) => $q->latest()->take(10)
        ],
        );
        // dd($user);
        return view('pages.private.admin.wholesale-client-users.show', compact('user'));
    }

    public function edit(string $id): View
    {
        $user = $this->wholesaleClientUserServices->findById($id);
        $userRole = $user->roles->first()?->name;
        $roles = $this->roleService->getByNames(['Wholesale']);
        $vatcountries = $this->vatRateService->options();
        // dd($vatcountries);
        return view('pages.private.admin.wholesale-client-users.form', compact('user', 'roles', 'userRole','vatcountries'));
    }

    public function update(UpdateWholesaleClientUserRequest $request, string $id): RedirectResponse
    {
        $data = $request->validated();
        $this->wholesaleClientUserServices->update($id, $request->validated());
        return redirect()->route('admin.wholeseller.index')->with('success', 'Wholesaler updated successfully.');
    }

    public function destroy(string $id): RedirectResponse
    {
        $this->wholesaleClientUserServices->delete($id);
        return redirect()->route('admin.wholeseller.index')->with('success', 'Wholesaler deleted successfully.');
    }

}
