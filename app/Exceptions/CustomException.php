<?php

namespace App\Exceptions;

class CustomException extends \Symfony\Component\HttpKernel\Exception\HttpException
{
    public function __construct($httpCode,$customJson,\Exception $previous = null,$code = 0)
    {
        parent::__construct($httpCode, $customJson, $previous, array(), $code);
    }
}
