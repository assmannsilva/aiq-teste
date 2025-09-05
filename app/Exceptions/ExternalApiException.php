<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class ExternalApiException extends Exception
{
    public function __construct(string $message, int $code, ?Throwable $previous)
    {
        parent::__construct($message, $code, $previous);
    }
}
