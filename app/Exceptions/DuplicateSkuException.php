<?php

namespace App\Exceptions;

use Exception;

/**
 * Class DuplicateSkuException
 * Thrown when attempting to assign a SKU that is already registered.
 */
class DuplicateSkuException extends Exception
{
    protected $message = 'SKU Produk sudah terdaftar dan harus bersifat unik.';
    protected $code = 422;

    public function __construct(string $message = '', int $code = 422, ?Exception $previous = null)
    {
        parent::__construct($message ?: $this->message, $code, $previous);
    }
}
