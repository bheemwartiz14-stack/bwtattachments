<?php
declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Quotation;
use App\Models\Order;
use App\Models\Subcategory;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $totalProducts = Product::count();
        $totalCategories = Category::count();
        $totalSubcategories = Subcategory::count();
        $totalClients = User::role('Wholesaler')->count();
        $totalOrder = Order::count();
        $totalUsers = User::count();
        $stats = [
            'total_products' => $totalProducts,
            'new_products' => Product::where('created_at', '>=', Carbon::now()->startOfMonth())->count(),
            'published_products' => Product::where('status', true)->count(),
            'total_categories' => $totalCategories,
            'new_categories' => Category::where('created_at', '>=', Carbon::now()->startOfMonth())->count(),
            'total_subcategories' => $totalSubcategories,
            'active_clients' => $totalClients,
            'new_clients' => User::role('Wholesaler')->where('created_at', '>=', Carbon::now()->startOfMonth())->count(),
            'total_order' => $totalOrder,
            'total_users' => $totalUsers,
            'active_users' => User::where('status', true)->count(),
        ];


        return view('pages.private.admin.dashboard', compact('stats'));
    }
}
