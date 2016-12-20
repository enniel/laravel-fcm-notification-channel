<?php

namespace NotificationChannels\FCM\Exceptions;

class CouldNotSendNotification extends \Exception
{
    /**
     * Thrown when recipient is missing.
     *
     * @return static
     */
    public static function missingRecipient()
    {
        return new static('Notification was not sent. You should specify device token(s), topic(s) or group(s) for sending notification.');
    }
}
