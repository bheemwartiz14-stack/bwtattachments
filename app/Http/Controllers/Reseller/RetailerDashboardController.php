<?php
declare(strict_types=1);
namespace App\Http\Controllers\Reseller;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Services\OrderServices;
use App\Services\QuotationService;
use Illuminate\View\View;

class RetailerDashboardController extends Controller
{
     public function __construct(
        protected QuotationService $quotationService, #DEPECTATED CODE
        protected OrderServices $orderServices,
    ) {}
   public function index(): View
    {
        $userId = auth()->id();
        $user = auth()->user();
        $orders = $this->orderServices->findByUser($userId);
        $companyName = $user->userMeta?->metadata['client_name'] ?? $user->userMeta?->metadata['retailer_client_name']  ?? $user->name;
        $lastLogin = $user->created_at;
        $stats = [
            'total_products' => Product::count(),
            'draft_orders'   => $orders->where('status', 'draft')->count(),
            'sent_orders'    => $orders->where('status', 'sent')->count(),
            'downloads'      => $orders->whereNotNull('pdf_file')->count(),
        ];
        $recentOrders = $orders->sortByDesc('created_at') ->take(5) ->load('items');
        $notifications = collect()->merge(
                $recentOrders->map(fn ($order) => [
                    'type'      => 'New PDF',
                    'message'   => "{$order->quotation_number} PDF generated",
                    'time'      => $order->created_at->diffForHumans(),
                    'created_at' => $order->created_at,
                    'icon'      => 'document-text',
                ])
            )
            ->merge(
                $orders
                    ->sortByDesc('updated_at')
                    ->take(3)
                    ->map(fn ($order) => [
                        'type'       => 'Order Update',
                        'message'    => "Order {$order->quotation_number} updated",
                        'time'       => $order->updated_at->diffForHumans(),
                        'created_at' => $order->updated_at,
                        'icon'       => 'currency-dollar',
                    ])
            )
            ->sortByDesc('created_at')
            ->take(5)
            ->values();

        return view('pages.private.reseller.dashboard', compact(
                'stats',
                'recentOrders',
                'notifications',
                'companyName',
                'lastLogin',
                'user'
            ));
    }
    //
}
