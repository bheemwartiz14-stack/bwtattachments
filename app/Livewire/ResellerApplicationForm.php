<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Events\ResellerApplicationSubmitted;
use App\Models\ResellerApplication;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class ResellerApplicationForm extends Component
{
    public string $company_name = '';
    public string $contact_person = '';
    public string $address = '';
    public string $postal_code = '';
    public string $place = '';
    public string $country = '';
    public string $telephone = '';
    public string $email = '';
    public string $website = '';
    public string $vat_number = '';
    public string $chamber_of_commerce = '';
    public string $additional_info = '';

    public bool $success = false;

    protected function rules(): array
    {
        return [
            'company_name' => ['required', 'string', 'max:255'],
            'contact_person' => ['required', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:255'],
            'postal_code' => ['nullable', 'string', 'max:50'],
            'place' => ['nullable', 'string', 'max:255'],
            'country' => ['nullable', 'string', 'max:255'],
            'telephone' => ['nullable', 'string', 'max:50'],
            'email' => ['required', 'email', 'max:255'],
            'website' => ['nullable', 'string', 'max:255'],
            'vat_number' => ['nullable', 'string', 'max:50'],
            'chamber_of_commerce' => ['nullable', 'string', 'max:100'],
            'additional_info' => ['nullable', 'string', 'max:2000'],
        ];
    }

    public function submit(): void
    {
        $data = $this->validate();

        $application = ResellerApplication::create($data);

        ResellerApplicationSubmitted::dispatch($application);
        $this->reset([
            'company_name', 'contact_person', 'address', 'postal_code',
            'place', 'country', 'telephone', 'email', 'website',
            'vat_number', 'chamber_of_commerce', 'additional_info',
        ]);
        $this->success = true;
    }

    public function render(): View
    {
        return view('livewire.reseller-application-form');
    }
}
