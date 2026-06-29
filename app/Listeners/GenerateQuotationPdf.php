<?php
declare(strict_types=1);

namespace App\Listeners;

use App\Events\QuotationCreated;
use Illuminate\Support\Facades\Log;

class GenerateQuotationPdf
{
    public function handle(QuotationCreated $event): void
    {
        Log::info('PDF generation triggered for quotation: ' . $event->quotation->quotation_number);
    }
}
