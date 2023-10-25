<?php

namespace Devertix\LaravelApiGenerator\Generators\Exceptions;

use Throwable;

class InvalidFieldTypeException extends \Exception
{
    public function __construct(string $message = "", int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->message = "This field type is not handled properly: " . $this->getMessage() .
            " in " . $this->getTrace()[0]['file'] . " (line: " . $this->getTrace()[0]['line'] . ")";

    }
}
