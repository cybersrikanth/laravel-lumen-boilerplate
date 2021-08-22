<?php

namespace App\Exceptions\Session;


class InvalidOrExpiredSessionException extends SessionException
{
    public function __construct($message = "Invalid/Expired session", $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}