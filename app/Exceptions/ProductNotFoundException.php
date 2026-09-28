<?php

namespace App\Exceptions;

use Exception;

/**
 * Class ProductNotFoundException
 * Thrown when a product is requested by ID or SKU but cannot be found in database.
 * Part of Error Handling & Debugging (UK J.620100.012.02).
 */
class ProductNotFoundException extends Exception
{
    protected $message = 'Produk dengan identifikasi tersebut tidak ditemukan dalam sistem.';
    protected $code = 404;

    public function __construct(string $message = '', int $code = 404, ?Exception $previous = null)
    {
        parent::__construct($message ?: $this->message, $code, $previous);
    }
}
