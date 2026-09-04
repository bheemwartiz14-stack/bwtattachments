<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\UserProductService;
use App\Services\VatRateService;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserProductController extends Controller
{
    public function __construct(
        private readonly UserProductService $userProductService,
        protected UserService $userService,
        protected VatRateService $vatRateService
    ) {}

    public function toggleFavorite(Product $product): JsonResponse
    {
        return response()->json(
            $this->userProductService->toggleFavorite(auth()->user(), $product)
        );
    }

    public function toggleCart(Product $product, \Illuminate\Http\Request $request): JsonResponse
    {
        $quantity = (int) $request->input('quantity', 1);
        $quantity = min(50, max(1, $quantity));

        // If already in cart, update to final selected quantity (not increment)
        $user = auth()->user();
        $isInCart = in_array((string) $product->id, $this->userProductService->getQuotationProductIds($user), true);
        if ($isInCart) {
            $this->userProductService->updateCartQuantity($user, (string) $product->id, $quantity);
            return response()->json([
                'success' => true,
                'added' => true,
                'cartCount' => $this->userProductService->getCartCount($user),
                'count' => $this->userProductService->getCartCount($user),
                'message' => 'Quantity updated to '.$quantity,
            ]);
        }

        $result = $this->userProductService->toggleCart(auth()->user(), $product);
        // Override quantity to selected qty if added
        if ($result['added']) {
            $this->userProductService->updateCartQuantity($user, (string) $product->id, $quantity);
        }

        return response()->json([
            'success' => true,
            'added' => $result['added'],
            'cartCount' => $this->userProductService->getCartCount($user),
            'count' => $this->userProductService->getCartCount($user),
            'message' => $result['message'],
        ]);
    }

    public function updateQuantity(Product $product, Request $request): JsonResponse
    {
        $quantity = (int) $request->input('quantity', 1);
        $current = $this->userProductService->getCartQuantity(auth()->user(), (string) $product->id);
        $quantity = $current + $quantity;
        if (! in_array((string) $product->id, $this->userProductService->getQuotationProductIds(auth()->user()), true)) {
            $this->userProductService->addToCart(auth()->user(), $product, $quantity);
        } else {
            $this->userProductService->updateCartQuantity(auth()->user(), (string) $product->id, $quantity);
        }
        return response()->json([
            'success' => true,
            'quantity' => $quantity,
            'cartCount' => $this->userProductService->getCartCount(auth()->user()),
        ]);
    }

    public function cart(): View
    {
        $user = auth()->user();
         $role = $user->roles->first()?->name;
        $salleruser = $role === 'Wholesaler' ? $this->userService->getAdminUser() : $this->userService->getParentUser();
        $vatList =$this->vatRateService->getTransactionVatCountryInfo($user, $salleruser);
        $cartItems = $this->userProductService->getQuotationItems($user);
        $cartIds = $cartItems->pluck('id')->map(fn ($id) => (string) $id)->toArray();
        return view('pages.public.cart.index', compact('cartItems', 'cartIds','vatList'));
    }
}
