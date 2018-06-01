<?php
declare(strict_types=1);

namespace JCS\LaravelGuzzle;

use DomainException;

class ClientNotRegisteredException extends DomainException
{
    public function __construct(string $clientName)
    {
        $message = sprintf("The client '%s' is not registered", $clientName);

        parent::__construct($message);
    }
}
