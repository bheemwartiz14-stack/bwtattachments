<?php

namespace App\Http\Controllers\Customers;
use App\Http\Controllers\Controller;
use App\Services\UserProductService;
use App\Services\OrderServices;
use App\Services\UserService;
use App\Services\VatRateService;
use App\Http\Requests\Customers\StoreCustomerOrderRequest;
use Illuminate\Http\RedirectResponse;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Http\Request;

class CustomersOrderManagemntController extends Controller
{
      public function __construct(
        protected UserProductService $userProductService,
        protected OrderServices $orderServices,
        protected UserService $userService,
        protected VatRateService $vatRateService
    ) {}
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orders = $this->orderServices->findByUser(auth()->id());
         return view('pages.private.customer.orders.index', compact('orders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = $this->userService->getAuthenticatedUser();
        $meta = $this->userService->getAuthenticatedUserMetadata();
        $orderNumber = $this->orderServices->generateOrderNumber();
        $resalleruser = $this->userService->getParentUser();
        $vatList =$this->vatRateService->getTransactionVatCountryInfo($user, $resalleruser);
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
        return view('pages.private.customer.orders.create', [
            'user' => $user,
            'meta' => $meta,
            'resalleruser' => $resalleruser,
            'cartIds' => $cartIds,
            'cartItemsJson' => $cartItemsJson,
            'orderNumber' => $orderNumber,
            'usermargin' => $usermargin,
              'vatList' => $vatList
        ]);
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCustomerOrderRequest $request)
    {
        $data = $request->validated();
        $action = $request->input('action', 'draft');
        $data['status'] = $action === 'send' ? 'sent' : 'draft';
        $customer_logo = $data['customer_logo_temp'];
        if($customer_logo){
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
        return redirect()->route('customer.orders.index')->with('success', $message);
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $order = $this->orderServices->findById($id);
        return view('pages.private.customer.orders.show', compact('order'));
        //
    }

    public function download(string $id): BinaryFileResponse|RedirectResponse|StreamedResponse
    {
        $order = $this->orderServices->findById($id);
        $order = $this->orderServices->generateOrderPdf($order);

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
}
