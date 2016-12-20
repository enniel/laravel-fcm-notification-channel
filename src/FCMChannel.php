<?php

namespace NotificationChannels\FCM;

use LaravelFCM\Sender\FCMSender;
use Illuminate\Support\Facades\Event;
use Illuminate\Notifications\Notification;
use NotificationChannels\FCM\Exceptions\CouldNotSendNotification;

class FCMChannel
{
    /**
     * @var \LaravelFCM\Sender\FCMSender
     */
    protected $sender;

    /**
     * Constructor.
     *
     * @param \LaravelFCM\Sender\FCMSender $sender
     */
    public function __construct(FCMSender $sender)
    {
        $this->sender = $sender;
    }

    /**
     * Send the given notification.
     *
     * @param mixed $notifiable
     * @param \Illuminate\Notifications\Notification $notification
     *
     * @throws \NotificationChannels\FCM\Exceptions\CouldNotSendNotification
     */
    public function send($notifiable, Notification $notification)
    {
        $message = $notification->toFCM($notifiable);
        if ($message->recipientNotGiven()) {
            if (! $to = $notifiable->routeNotificationFor('FCM')) {
                throw CouldNotSendNotification::missingRecipient();
            }
            $message->to($to);
        }
        $method = 'sendTo';
        if ($message instanceof FCMMessageTopic) {
            $method .= 'Topic';
        } elseif ($message instanceof FCMMessageGroup) {
            $method .= 'Group';
        }

        $response = $this->sender->{$method}(...$message->getArgs());

        Event::fire(new MessageWasSended($response, $notifiable));
    }
}
