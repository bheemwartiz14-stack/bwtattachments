<?php

declare(strict_types=1);

namespace App\Listeners;

use Illuminate\Mail\Events\MessageSending;
use Illuminate\Mail\Events\MessageSent;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;

/**
 * Global trigger that logs EVERY outgoing email to the `email`
 * log channel (storage/logs/email.log).
 *
 * Registered in App\Providers\EventServiceProvider for:
 *  - MessageSending (Status: sending)
 *  - MessageSent (Status: sent successfully)
 *
 * Each trigger writes one block like:
 *
 *   --------------------------------------------------
 *   From: sender@example.com
 *   To: receiver@example.com
 *   Subject: ...
 *   Status: sending
 *   Email Sent Date: 2026-09-23 08:33:50
 *   Message / Body: ...
 *   --------------------------------------------------
 *
 * If a send throws, only the "sending" block exists — the exception
 * itself is logged by Laravel in laravel.log.
 */
class LogOutgoingEmail
{
    public function handle(MessageSending|MessageSent $event): void
    {
        $status = $event instanceof MessageSent ? 'sent successfully' : 'sending';

        /** @var Email $message */
        $message = $event instanceof MessageSent
            ? $event->sent->getOriginalMessage()
            : $event->message;

        $from = $this->formatAddresses($message->getFrom());
        $to = $this->formatAddresses($message->getTo());
        $cc = $this->formatAddresses($message->getCc());
        $subject = $message->getSubject() ?? '(no subject)';
        $sentDate = now()->format('Y-m-d H:i:s');
        $mailable = $this->detectMailableClass();

        $lines = [];
        $lines[] = '--------------------------------------------------';
        $lines[] = 'From: '.($from !== '' ? $from : '(none)');
        $lines[] = 'To: '.($to !== '' ? $to : '(none)');

        if ($cc !== '') {
            $lines[] = 'Cc: '.$cc;
        }

        $lines[] = 'Subject: '.$subject;
        $lines[] = 'Status: '.$status;
        $lines[] = 'Email Sent Date: '.$sentDate;

        if ($mailable !== null) {
            $lines[] = 'Mailable: '.$mailable;
        }

        $lines[] = '--------------------------------------------------';

        // Email logging must never break the request (e.g. unwritable
        // log file). Best effort: try the email channel, fall back to
        // the default channel, and swallow failures otherwise.
        try {
            Log::channel('email')->info(implode("\n", $lines));
        } catch (\Throwable $e) {
            try {
                Log::warning('Could not write to email.log: '.$e->getMessage());
            } catch (\Throwable $ignored) {
                // intentionally ignored: logging must not throw
            }
        }
    }

    /**
     * @param Address[]|string[] $addresses
     */
    protected function formatAddresses(array $addresses): string
    {
        $parts = [];

        foreach ($addresses as $address) {
            if ($address instanceof Address) {
                $email = $address->getAddress();
                $name = $address->getName();
                $parts[] = $name !== '' ? "{$name} <{$email}>" : $email;
            } else {
                $parts[] = (string) $address;
            }
        }

        return implode(', ', $parts);
    }

    /**
     * Best-effort detection of the Mailable class that triggered the send
     * by walking the backtrace for the first Mailable subclass.
     */
    protected function detectMailableClass(): ?string
    {
        foreach (debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS) as $frame) {
            $class = $frame['class'] ?? null;

            if (is_string($class) && class_exists($class) && is_subclass_of($class, Mailable::class)) {
                return $class;
            }
        }

        return null;
    }
}
