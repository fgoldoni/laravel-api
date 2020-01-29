<?php

namespace App\Exceptions;

use FrittenKeeZ\Vouchers\Exceptions\VoucherException;

class VoucherNotFoundException extends VoucherException
{
    /**
     * Exception message.
     *
     * @var string
     */
    protected $message = 'Coupon was not found with the provided code or user.';

    /**
     * Exception code - we use 404 Not Found.
     *
     * @var int
     */
    protected $code = 404;
}
