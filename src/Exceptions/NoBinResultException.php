<?php

namespace app\Exceptions;

class NoBinResultException extends \Exception
{
    public function __construct(string $message = "", int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct('error!', $code, $previous);
    }
}