<?php
declare(strict_types=1);

namespace App\Livewire;

use App\Services\ContactMessageService;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class ContactForm extends Component
{
    public string $name = '';
    public string $email = '';
    public string $company = '';
    public string $phone = '';
    public string $subject = '';
    public string $message = '';

    public bool $success = false;

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:2', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'company' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:255'],
            'subject' => ['required', 'string', 'min:3', 'max:255'],
            'message' => ['required', 'string', 'min:10', 'max:5000'],
        ];
    }

    public function submit(ContactMessageService $contactMessageService): void
    {
        $data = $this->validate();

        $contactMessageService->create($data);

        $this->reset(['name', 'email', 'company', 'phone', 'subject', 'message']);
        $this->success = true;
    }

    public function render(): View
    {
        return view('livewire.contact-form');
    }
}
