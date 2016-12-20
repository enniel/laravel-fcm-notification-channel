<?php

namespace NotificationChannels\FCM;

use LaravelFCM\Message\Topics;
use NotificationChannels\FCM\Exceptions\InvalidArgumentException;

class FCMMessageTopic extends FCMMessage
{
    /**
     * {@inheritdoc}
     */
    public function to($recipient)
    {
        if (! ($recipient instanceof Topics)) {
            $type = gettype($data);
            if (is_object($data)) {
                $type = get_class($data);
            }

            return new InvalidArgumentException(
               'The argument for %s::%s must be instanceof %s, null or array. %s given.',
                [
                    self::class, 'to', Topics::class, $type,
                ]
            );
        }

        return parent::to($recipient);
    }
}
