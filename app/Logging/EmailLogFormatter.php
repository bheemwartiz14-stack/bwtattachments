<?php

declare(strict_types=1);

namespace App\Logging;

use Illuminate\Log\Logger;
use Monolog\Formatter\LineFormatter;

/**
 * Tap for the `email` log channel.
 *
 * Outputs ONLY the message itself (our pre-formatted block) so
 * storage/logs/email.log stays clean — no "[date] local.INFO:" prefix,
 * no JSON context appended. Multi-line messages are preserved.
 *
 * Usage in config/logging.php:
 *   'email' => [
 *       'driver' => 'single',
 *       'path' => storage_path('logs/email.log'),
 *       'tap' => [App\Logging\EmailLogFormatter::class],
 *       ...
 *   ],
 */
class EmailLogFormatter
{
    /**
     * NOTE: Laravel passes the Illuminate logger here (not Monolog),
     * so the signature must accept Illuminate\Log\Logger.
     */
    public function __invoke(Logger $logger): void
    {
        $formatter = new LineFormatter("%message%\n", null, true, true);

        foreach ($logger->getLogger()->getHandlers() as $handler) {
            $handler->setFormatter($formatter);
        }
    }
}
