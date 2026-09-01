<?php

declare(strict_types=1);

namespace App\Http\Controllers\Public;

use App\Data\UserData;
use App\Events\ContactMessageSubmitted;
use App\Events\QuotationCreated;
use App\Events\ResellerApplicationSubmitted;
use App\Events\UpdateUserMargins;
use App\Events\WelcomeOnboardingUser;
use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Models\Quotation;
use App\Models\ResellerApplication;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        return view('pages.public.index');
    }

        public function send_email(): JsonResponse
    {
        Mail::raw('Hello', function ($message) {
            $message
                ->to('bheem.wartiz14@gmail.com')
                ->subject('Test Email');
        });

        return response()->json([
            'success' => true,
            'message' => 'Test email sent successfully.',
        ]);
    }
}
