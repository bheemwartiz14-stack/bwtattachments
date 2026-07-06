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

    public bool $success = false;

    protected function rules(): array
    {
        return [
            'company_name' => ['required', 'string', 'max:255'],
            'contact_person' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'postal_code' => ['required', 'string', 'max:50'],
            'place' => ['required', 'string', 'max:255'],
            'country' => ['required', 'string', 'max:255'],
            'telephone' => ['required', 'string', 'max:50'],
            'email' => ['required', 'email', 'max:255'],
            'website' => ['nullable', 'string', 'max:255'],
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
        ]);
        $this->success = true;
    }

    public function render(): View
    {
        return view('livewire.reseller-application-form');
    }
}
