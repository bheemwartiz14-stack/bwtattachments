<x-mail::message>
# New Contact Message

**Name:** {{ $contactMessage->name }}

**Email:** {{ $contactMessage->email }}

**Subject:** {{ $contactMessage->subject }}

**Message:**

{{ $contactMessage->message }}
</x-mail::message>
