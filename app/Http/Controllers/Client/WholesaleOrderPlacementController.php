<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Services\UserProductService;
use App\Services\OrderServices;
use App\Services\VatRateService;
use App\Services\UserService;
use App\Http\Requests\Client\Order\StoreWholesaleOrderRequest;
use Illuminate\Http\RedirectResponse;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Support\Facades\Storage;

use Illuminate\View\View;

class WholesaleOrderPlacementController extends Controller
{
    public function __construct(
        protected UserProductService $userProductService,
        protected OrderServices $orderServices,
        protected UserService $userService,
        protected VatRateService $vatRateService
    ) {}
    public function index(): View
    {
        $orders = $this->orderServices->findByUser(auth()->id());
        return view('pages.private.client.orders.index', compact('orders'));
    }

    public function create(): View
    {
        $user = $this->userService->getAuthenticatedUser();
        $meta = $this->userService->getAuthenticatedUserMetadata();
        $orderNumber = $this->orderServices->generateOrderNumber();
        $admin = $this->userService->getAdminUser();
        $vatList =$this->vatRateService->getTransactionVatCountryInfo($user, $admin);
        $cartIds = $this->userProductService->getQuotationProductIds($user);
        $usermargin = $user?->userMargin?->margin_value ?? 0;
        $cartItems = $this->userProductService->getQuotationItems($user);
        $cart = session()->get('quotation_cart_'.(string) $user->id, []);
        if (! is_array($cart)) $cart = [];
        $productPrices = app(\App\Services\ProductService::class)->getActiveProductsWithUserPrices($user->id)->keyBy('id');
        $cartItemsJson = $cartItems->map(function ($product) use ($cart, $productPrices) {
            $pid = (string) $product->id;
            $priced = $productPrices->get($product->id);
            $price = $priced?->productPrices->first()?->final_price ?? $product->ddp_price ?? 0;
            return [
                'product_id' => $pid,
                'product_title' => $product->product_title,
                'product_code' => $product->product_code,
                'quantity' => max(1, (int) ($cart[$pid] ?? 1)),
                'price' => (float) $price,
            ];
        })->values()->toArray();
        return view('pages.private.client.orders.create', [
            'user' => $user,
            'meta' => $meta,
            'admin' => $admin,
            'cartIds' => $cartIds,
            'cartItemsJson' => $cartItemsJson,
            'orderNumber' => $orderNumber,
            'usermargin' => $usermargin,
            'vatList' => $vatList
        ]);
    }

     public function store(StoreWholesaleOrderRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $action = $request->input('action', 'draft');
        $data['status'] = $action === 'send' ? 'sent' : 'draft';
        $wholesale_client_logo = $data['wholesale_client_logo_temp'];
        if($wholesale_client_logo){
            $this->userService->updateUserCompayLogo($data);
        }
        $order = $this->orderServices->create($data);
        if (in_array($action, ['pdf', 'send'])) {
            $this->orderServices->generateOrderPdf($order);
        }
        if ($action === 'send') {
            $this->orderServices->sendEmail($order);
        }
        session()->forget('quotation_cart_'.(string) $request->user()->id);
        $message = match ($action) {
            'pdf' => 'Order created and PDF generated successfully.',
            'send' => 'Order created successfully, PDF generated, and sent to the admin successfully.',
            default => 'Order saved as draft successfully.',
        };
        return redirect()->route('client.orders.index')->with('success', $message);
    }

     public function show(string $id): View
    {
        $order = $this->orderServices->findById($id);
        return view('pages.private.client.orders.show', compact('order'));
    }

       public function download(string $id): BinaryFileResponse|RedirectResponse|StreamedResponse
    {
        $order = $this->orderServices->findById($id);
        $this->orderServices->generateOrderPdf($order);
        if (!$order->pdf_file || !Storage::disk('public')->exists($order->pdf_file)) {
            return back()->with('error', 'PDF file not found.');
        }
        return Storage::disk('public')->download($order->pdf_file);
    }


       public function sendEmail(string $id): RedirectResponse
    {
        $order = $this->orderServices->findById($id);

        $fromId = $order->order_from_user_id ?? $order->user_id ?? null;
        if ((string) $fromId !== (string) auth()->id()) {
            abort(403);
        }
        $this->orderServices->generateOrderPdf($order);
        $this->orderServices->sendEmail($order);
        $this->orderServices->update($id, ['status' => 'sent']);
        return back()->with('success', 'Order sent successfully.');
    }
    public function preview(string $id)
    {
        return $this->orderServices->previewPdf($id);
    }
}
