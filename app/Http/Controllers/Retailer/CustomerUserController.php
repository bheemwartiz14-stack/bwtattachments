<?php

namespace App\Http\Controllers\Retailer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Retailer\Customer\{StoreCustomerClientUserRequest, UpdateCustomerClientUserRequest};
use App\Services\RoleService;
use App\Services\Retailer\CustomerService;
use Illuminate\Http\Request;

class CustomerUserController extends Controller
{
    public function __construct(
        protected RoleService $roleService,
        protected CustomerService $customerService,
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filters = $request->only(['search', 'role', 'status']);
         $users = $this->customerService->paginate(10, $filters);
         return view('retailer.customer-users.index', compact(
            'users',
        ));

        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = $this->roleService->getByNames(['customer']);
        return view('retailer.customer-users.form', compact('roles'));
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCustomerClientUserRequest $request)
    {
        $data = $request->validated();
        $response = $this->customerService->create($data);
        return redirect()->route('retailer.customer-users.index') ->with('success', 'Customer account created successfully.');
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
         $user = $this->customerService->findById($id);
         $user->load(['userMeta', 'userMargin', 'quotations' => fn ($q) => $q->latest()->take(10)]);
        return view('retailer.customer-users.show', compact('user'));
    }
        //


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
         $user = $this->customerService->findById($id);
          $userRole = $user->roles->first()?->name;
           $meta = $user->userMeta?->metadata ?? [];
           return view('retailer.customer-users.form', compact('user', 'userRole', 'meta'));
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCustomerClientUserRequest $request, string $id)
    {
         $this->customerService->update($id, $request->validated());
           return redirect()->route('retailer.customer-users.index') ->with('success', 'Customer account Updated successfully.');


        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
          $this->customerService->delete($id);
        return redirect()->route('client.retailer-users.index')->with('success', 'Customer account Updated successfully.');
        //
    }
}
