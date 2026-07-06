<x-mail::message>
# New Reseller Application

A new reseller application has been submitted.

**Company name:** {{ $application->company_name }}
**Contact person:** {{ $application->contact_person }}
**Address:** {{ $application->address }}
**Postal code:** {{ $application->postal_code }}
**Place:** {{ $application->place }}
**Country:** {{ $application->country }}
**Telephone:** {{ $application->telephone }}
**Email:** {{ $application->email }}
**Website:** {{ $application->website ?? 'N/A' }}

<x-mail::button :url="url('/admin')">
View in Admin
</x-mail::button>
</x-mail::message>
