<?php

namespace NotificationChannels\FCM;

class MessageWasSended
{
    /**
     * @var mixed
     */
    public $response;

    /**
     * @var object
     */
    public $notifiable;

    /**
     * @param  mixed  $response
     * @param  object $notifiable
     *
     * @return void
     */
    public function __construct($response, $notifiable)
    {
        $this->response = $response;
        $this->notifiable = $notifiable;
    }
}
