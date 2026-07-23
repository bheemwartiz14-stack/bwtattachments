<?php
declare(strict_types=1);

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        return view('pages.public.index');
    }

    public function send_email(): JsonResponse
    {
        try {
            $to = 'bheem.wartiz14@gmail.com';

            Mail::raw('This is a test email from BWT Attachments.', function ($message) use ($to) {
                $message->to($to)
                    ->subject('Test Email from BWT Attachments');
            });

            return response()->json([
                'success' => true,
                'message' => 'Test email sent successfully.',
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send test email: ' . $e->getMessage(),
            ], 500);
        }
    }
}
