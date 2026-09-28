<?php

namespace App\Exceptions;

use Exception;

/**
 * Class InsufficientStockException
 * Thrown when an operation tries to reduce stock beyond current availability.
 */
class InsufficientStockException extends Exception
{
    protected $message = 'Stok produk tidak mencukupi untuk operasi yang diminta.';
    protected $code = 400;

    public function __construct(string $message = '', int $code = 400, ?Exception $previous = null)
    {
        parent::__construct($message ?: $this->message, $code, $previous);
    }
}
