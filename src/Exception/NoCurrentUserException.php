<?php

declare(strict_types=1);

namespace App\Exception;

final class NoCurrentUserException extends \RuntimeException
{
    public function __construct(string $message = 'No current user', int $code = 0, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
