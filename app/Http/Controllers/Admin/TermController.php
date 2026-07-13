<?php
declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\TermService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TermController extends Controller
{
    public function __construct(
        protected TermService $termService,
    ) {}

    public function index(): View
    {
        $terms = $this->termService->getAll();

        return view('pages.private.admin.terms.index', compact('terms'));
    }

    public function create(): View
    {
        return view('pages.private.admin.terms.form');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'file' => 'required|file|mimes:pdf,doc,docx|max:10240',
        ]);

        $data['created_by'] = auth()->id();
        $data['is_active'] = true;

        $this->termService->create($data, $request->file('file'));

        return redirect()
            ->route('admin.terms.index')
            ->with('success', 'Term created successfully.');
    }

    public function edit(string $id): View
    {
        $term = $this->termService->findById($id);

        abort_if(!$term, 404);

        return view('pages.private.admin.terms.form', compact('term'));
    }

    public function update(Request $request, string $id): RedirectResponse
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'is_active' => 'nullable|boolean',
            'file' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
        ]);

        $this->termService->update($id, $data, $request->file('file'));

        return redirect()
            ->route('admin.terms.index')
            ->with('success', 'Term updated successfully.');
    }

    public function destroy(string $id): RedirectResponse
    {
        $this->termService->delete($id);

        return redirect()
            ->route('admin.terms.index')
            ->with('success', 'Term deleted successfully.');
    }
}
