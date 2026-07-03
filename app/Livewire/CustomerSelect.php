<?php
declare(strict_types=1);

namespace App\Livewire;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class CustomerSelect extends Component
{
    public string $search = '';

    public ?string $selectedId = null;

    public function selectCustomer(string $id): void
    {
        $customer = User::where('parent_id', auth()->id())
            ->role('Retailer')
            ->find($id, ['id', 'name', 'email', 'phone']);

        if ($customer) {
            $this->selectedId = $customer->id;
            $this->dispatch('customerSelected',
                id: $customer->id,
                name: $customer->name,
                email: $customer->email,
                phone: $customer->phone ?? '',
            );
        }
    }

    public function clearCustomer(): void
    {
        $this->selectedId = null;
        $this->search = '';
        $this->dispatch('customerCleared');
    }

    public function render(): View
    {
        $customers = User::where('parent_id', auth()->id())
            ->role('Retailer')
            ->orderBy('name')
            ->get(['id', 'name', 'email', 'phone']);

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
