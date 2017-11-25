<?php

namespace NotificationChannels\FCM\Test;

use Mockery;
use LaravelFCM\Sender\FCMSender;
use Illuminate\Events\Dispatcher;
use Illuminate\Notifications\Notifiable;
use NotificationChannels\FCM\FCMChannel;
use NotificationChannels\FCM\FCMMessage;
use Illuminate\Notifications\Notification;
use LaravelFCM\Response\DownstreamResponse;
use NotificationChannels\FCM\MessageWasSended;
use NotificationChannels\FCM\Exceptions\CouldNotSendNotification;

class FCMChannelTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->sender = Mockery::mock(FCMSender::class);
        $this->events = Mockery::mock(Dispatcher::class);
        $this->channel = new FCMChannel($this->sender, $this->events);
    }

    public function tearDown()
    {
        Mockery::close();
        parent::tearDown();
    }

    /** @test */
    public function it_can_send_a_notification()
    {
        $notifiable = new TestNotifiableWithArrayOfTokens;
        $notification = new TestNotification;
        $message = $notification->toFCM($notifiable);
        $to = $notifiable->routeNotificationFor('FCM');
        $message->to($to);
        $args = $message->getArgs();
        $this->sender->shouldReceive('sendTo')->with(...$args)->andReturn(Mockery::mock(DownstreamResponse::class));
        $this->events->shouldReceive('fire')->with(Mockery::type(MessageWasSended::class));
        $result = $this->channel->send($notifiable, $notification);
        $this->assertInstanceOf(DownstreamResponse::class, $result);
    }

    /** @test */
    public function it_return_null_with_recipient_empty_array()
    {
        $notifiable = new TestNotifiableWithEmptyArrayOfTokens;
        $notification = new TestNotification;
        $message = $notification->toFCM($notifiable);
        $to = $notifiable->routeNotificationFor('FCM');
        $message->to($to);
        $args = $message->getArgs();
        $this->sender->shouldNotReceive('sendTo');
        $this->events->shouldNotReceive('fire');
        $result = $this->channel->send($notifiable, $notification);
        $this->assertNull($result);
    }

    /** @test */
    public function it_throw_could_not_send_notification_exception()
    {
        $this->setExpectedException(CouldNotSendNotification::class);
        $notifiable = new TestNotifiableWithInvalidRecipient;
        $notification = new TestNotification;
        try {
            $this->channel->send($notifiable, $notification);
        } catch (CouldNotSendNotification $e) {
            $this->assertEquals(
              'Notification was not sent. You should specify device token(s), topic(s) or group(s) for sending notification.',
              $e->getMessage()
            );
            throw $e;
        }
    }
}

class TestNotifiableWithArrayOfTokens
{
    use Notifiable;

    public function routeNotificationForFCM()
    {
        return ['test_token'];
    }
}

class TestNotifiableWithEmptyArrayOfTokens
{
    use Notifiable;

    public function routeNotificationForFCM()
    {
        return [];
    }
}

class TestNotifiableWithInvalidRecipient
{
    use Notifiable;

    public function routeNotificationForFCM()
    {
    }
}

class TestNotification extends Notification
{
    public function toFCM($notifiable)
    {
        return new FCMMessage();
    }
}
