<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\TermService;
use Illuminate\View\View;

class TermController extends Controller
{
    public function __construct(
        protected TermService $termService,
    ) {}

    public function index(): View
    {
        $terms = $this->termService->getActive();

        return view('pages.public.terms.index', compact('terms'));
    }
}
