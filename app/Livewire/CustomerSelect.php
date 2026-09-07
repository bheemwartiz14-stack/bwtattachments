<?php

declare(strict_types=1);

namespace App\Livewire;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use App\Services\VatRateService;
use Livewire\Component;

class CustomerSelect extends Component
{
    public string $search = '';

    public ?string $selectedId = null;

    public Collection $users;
    protected VatRateService $vatRateService;
    public function boot(VatRateService $vatRateService): void
    {
        $this->vatRateService = $vatRateService;
    }
    public function mount(?string $selectedId = null): void
    {
        if ($selectedId) {
            $this->selectedId = $selectedId;
            $customer = $this->users->firstWhere('id', $selectedId);
            $vatList =$this->vatRateService->getTransactionVatCountryInfo(auth()->user(), $customer);
            if ($customer) {
                $margin = $customer->userMargin?->margin_value ?? 0;
                $this->dispatch(
                    'customerSelected',
                    id: $customer->id,
                    name: $customer->name,
                    email: $customer->email,
                    phone: $customer->phone ?? '',
                    vatList: $vatList ?? '',
                    margin: (float) $margin,
                );
                $this->dispatch('customerIdChanged', id: $customer->id,iso_code:$vatList['iso_code'] ?? '' ,standard_vat_rate:  $vatList['standard_vat_rate'] ?? '' );
            }
        }
    }

    public function selectCustomer(string $id): void
    {
        $customer = $this->users->firstWhere('id', $id);
        $vatList =$this->vatRateService->getTransactionVatCountryInfo(auth()->user(), $customer);
        if ($customer) {
            $this->selectedId = $customer->id;
            $margin = $customer->userMargin?->margin_value ?? 0;
            $this->dispatch(
                'customerSelected',
                id: $customer->id,
                name: $customer->name,
                email: $customer->email,
                phone: $customer->phone ?? '',
                iso_code: $vatList['iso_code'] ?? '',
                standard_vat_rate: $vatList['standard_vat_rate'] ?? '',
                margin: (float) $margin,
            );
            $this->dispatch('customerIdChanged', id: $customer->id,iso_code:$vatList['iso_code'] ?? '' ,standard_vat_rate:  $vatList['standard_vat_rate'] ?? '' );
        }
    }

    public function clearCustomer(): void
    {
        $this->selectedId = null;
        $this->search = '';
        $this->dispatch('customerCleared');
        $this->dispatch('customerIdChanged', id: null);
    }

    public function render(): View
    {
        $customers = $this->users;

        if ($this->search) {
            $s = strtolower($this->search);
            $customers = $customers->filter(function ($c) use ($s) {
                return stripos($c->name, $s) !== false
                    || stripos($c->email, $s) !== false
                    || ($c->phone && stripos($c->phone, $s) !== false);
            })->values();
        }

        return view('livewire.customer-select', [
            'customers' => $customers,
        ]);
    }
}
