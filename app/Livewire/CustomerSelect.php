<?php
declare(strict_types=1);

namespace App\Livewire;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Livewire\Component;

class CustomerSelect extends Component
{
    public string $search = '';

    public ?string $selectedId = null;

    public Collection $users;

    public function mount(?string $selectedId = null): void
    {
        if ($selectedId) {
            $this->selectedId = $selectedId;
            $customer = $this->users->firstWhere('id', $selectedId);
            if ($customer) {
                $this->dispatch('customerSelected',
                    id: $customer->id,
                    name: $customer->name,
                    email: $customer->email,
                    phone: $customer->phone ?? '',
                );
            }
        }
    }

    public function selectCustomer(string $id): void
    {
        $customer = $this->users->firstWhere('id', $id);

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
