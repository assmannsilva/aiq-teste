<?php

namespace App\Exceptions;

use Exception;
use Throwable;

// Exception criada especificamente para renderizar respostas de erro da API externa, se precisar
class ExternalApiException extends Exception
{
    public function __construct(string $message, int $code, ?Throwable $previous)
    {
        parent::__construct($message, $code, $previous);
    }
}
