<?php
declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ContactMessageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContactListController extends Controller
{
    public function __construct(
        protected ContactMessageService $contactMessageService,
    ) {}

    public function index(Request $request): View
    {
        return view('admin.contact-list.index', [
            'messages' => $this->contactMessageService->paginate(
                (int) $request->query('per_page', 15),
            ),
        ]);
    }

    public function destroy(string $id): RedirectResponse
    {
        $this->contactMessageService->delete($id);
        return redirect()->route('admin.contact-list.index')
            ->with('success', 'Message deleted successfully.');
    }
}
