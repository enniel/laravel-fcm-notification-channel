<?php

namespace NotificationChannels\FCM\Exceptions;

class InvalidArgumentException extends \InvalidArgumentException
{
    public function __construct($format, array $args = [])
    {
        parent::__construct(sprintf($format, ...$args));
    }
}
