<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Services\CompanyService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CompanyController extends Controller
{
    public function __construct(protected CompanyService $companyService) {}

    public function index(): View
    {
        $this->authorize('viewAny', Company::class);

        $companies = $this->companyService->paginate(10);

        return view('admin.companies.index', compact('companies'));
    }

    public function create(): View
    {
        $this->authorize('create', Company::class);

        return view('admin.companies.form');
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', Company::class);

        $validated = $request->validate([
            'company_name' => ['required', 'string', 'max:255'],
            'logo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
            'status' => ['boolean'],
        ]);

        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('companies', 'public');
        }

        $this->companyService->create($validated);

        return redirect()->route('admin.companies.index')->with('success', 'Company created successfully.');
    }

    public function edit(string $id): View
    {
        $this->authorize('update', Company::class);

        $company = $this->companyService->findById($id);

        return view('admin.companies.form', compact('company'));
    }

    public function update(Request $request, string $id): RedirectResponse
    {
        $this->authorize('update', Company::class);

        $validated = $request->validate([
            'company_name' => ['required', 'string', 'max:255'],
            'logo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
            'status' => ['boolean'],
        ]);

        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('companies', 'public');
        }

        $this->companyService->update($id, $validated);

        return redirect()->route('admin.companies.index')->with('success', 'Company updated successfully.');
    }

    public function destroy(string $id): RedirectResponse
    {
        $this->authorize('delete', Company::class);

        $this->companyService->delete($id);

        return redirect()->route('admin.companies.index')->with('success', 'Company deleted successfully.');
    }
}
