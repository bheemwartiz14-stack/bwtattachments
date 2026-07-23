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
        $results = [];

        $user = User::first();
        if ($user) {
            try {
                event(new WelcomeOnboardingUser($user, 'test-password', 'wholesale'));
                $results[] = 'WelcomeOnboardingUser: ok';
            } catch (\Throwable $e) {
                $results[] = 'WelcomeOnboardingUser: ' . $e->getMessage();
            }
        } else {
            $results[] = 'WelcomeOnboardingUser: skipped (no user)';
        }

        $contactMessage = ContactMessage::first();
        if ($contactMessage) {
            try {
                event(new ContactMessageSubmitted($contactMessage));
                $results[] = 'ContactMessageSubmitted: ok';
            } catch (\Throwable $e) {
                $results[] = 'ContactMessageSubmitted: ' . $e->getMessage();
            }
        } else {
            $results[] = 'ContactMessageSubmitted: skipped (no contact message)';
        }

        $quotation = Quotation::first();
        if ($quotation) {
            try {
                event(new QuotationCreated($quotation));
                $results[] = 'QuotationCreated: ok';
            } catch (\Throwable $e) {
                $results[] = 'QuotationCreated: ' . $e->getMessage();
            }
        } else {
            $results[] = 'QuotationCreated: skipped (no quotation)';
        }

        $application = ResellerApplication::first();

        if ($application) {
            try {
                event(new ResellerApplicationSubmitted($application));
                $results[] = 'ResellerApplicationSubmitted: ok';
            } catch (\Throwable $e) {
                $results[] = 'ResellerApplicationSubmitted: ' . $e->getMessage();
            }
        } else {
            $results[] = 'ResellerApplicationSubmitted: skipped (no application)';
        }

        try {
            event(new UpdateUserMargins(
                new UserData(
                    user_id: $user?->id ?? 'test-id',
                    parent_id: null,
                    role_name: 'wholesale',
                    name: $user?->name ?? 'Test',
                    margin_type: 'percentage',
                    type: 'wholesale',
                    margin_value: 10.0,
                )
            ));
            $results[] = 'UpdateUserMargins: ok';
        } catch (\Throwable $e) {
            $results[] = 'UpdateUserMargins: ' . $e->getMessage();
        }

        return response()->json([
            'success' => true,
            'results' => $results,
        ]);
    }
}
