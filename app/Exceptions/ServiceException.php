<?php
namespace App\Exceptions;

class ServiceException extends \Exception
{

    public function __construct(string $message)
    {
        $this->message = $message;
    }
}
