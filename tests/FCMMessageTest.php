<?php

namespace NotificationChannels\FCM\Test;

use LaravelFCM\Message\Options;
use LaravelFCM\Message\PayloadData;
use LaravelFCM\Message\OptionsBuilder;
use NotificationChannels\FCM\FCMMessage;
use LaravelFCM\Message\OptionsPriorities;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotification;
use LaravelFCM\Message\PayloadNotificationBuilder;
use NotificationChannels\FCM\Exceptions\InvalidArgumentException;

class FCMMessageTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function it_construct_with_options_from_builder()
    {
        $message = new FCMMessage();

        $this->assertNull($message->getOptions());

        $options = new OptionsBuilder();
        $options->setCollapseKey('collapseKey')
                ->setContentAvailable(true)
                ->setPriority(OptionsPriorities::high)
                ->setDelayWhileIdle(true)
                ->setDryRun(true)
                ->setRestrictedPackageName('customPackageName')
                ->setTimeToLive(200);

        $message->options($options);

        $this->assertInstanceOf(Options::class, $message->getOptions());
    }

    /** @test */
    public function it_construct_with_options_from_instance()
    {
        $message = new FCMMessage();

        $this->assertNull($message->getOptions());

        $options = new OptionsBuilder();
        $options->setCollapseKey('collapseKey')
                ->setContentAvailable(true)
                ->setPriority(OptionsPriorities::high)
                ->setDelayWhileIdle(true)
                ->setDryRun(true)
                ->setRestrictedPackageName('customPackageName')
                ->setTimeToLive(200);

        $message->options($options->build());

        $this->assertInstanceOf(Options::class, $message->getOptions());
    }

    /** @test */
    public function it_construct_with_options_from_array()
    {
        $message = new FCMMessage();

        $this->assertNull($message->getOptions());

        $message->options([
            'collapse_key' => 'collapseKey',
            'content_available' => true,
            'priority' => OptionsPriorities::high,
            'delay_while_idle' => true,
            'dry_run' => true,
            'restricted_package_name' => 'customPackageName',
            'time_to_live' => 200,
        ]);

        $this->assertInstanceOf(Options::class, $message->getOptions());
    }

    /** @test */
    public function it_construct_with_options_and_throw_exception()
    {
        $this->setExpectedException(InvalidArgumentException::class);
        try {
            (new FCMMessage())->options('foo');
        } catch (InvalidArgumentException $e) {
            $this->assertEquals(
                sprintf(
                    'The argument for %s::%s must be instanceof %s, %s, null or array. %s given.',
                    FCMMessage::class,
                    'options',
                    Options::class,
                    OptionsBuilder::class,
                    'string'
                ),
                $e->getMessage()
            );
            throw $e;
        }
    }

    /** @test */
    public function it_construct_with_data_from_builder()
    {
        $message = new FCMMessage();

        $this->assertNull($message->getData());

        $data = new PayloadDataBuilder();
        $data->addData(['foo' => 'bar'])
             ->addData(['baz' => true]);

        $message->data($data);

        $this->assertInstanceOf(PayloadData::class, $message->getData());
    }

    /** @test */
    public function it_construct_with_data_from_instance()
    {
        $message = new FCMMessage();

        $this->assertNull($message->getData());

        $data = new PayloadDataBuilder();
        $data->addData(['foo' => 'bar'])
             ->addData(['baz' => true]);

        $message->data($data->build());

        $this->assertInstanceOf(PayloadData::class, $message->getData());
    }

    /** @test */
    public function it_construct_with_data_from_array()
    {
        $message = new FCMMessage();

        $this->assertNull($message->getData());

        $message->data([
            'foo' => 'bar',
            'baz' => true,
        ]);

        $this->assertInstanceOf(PayloadData::class, $message->getData());
    }

    /** @test */
    public function it_construct_with_data_and_throw_exception()
    {
        $this->setExpectedException(InvalidArgumentException::class);
        try {
            (new FCMMessage())->data('foo');
        } catch (InvalidArgumentException $e) {
            $this->assertEquals(
                sprintf(
                    'The argument for %s::%s must be instanceof %s, %s, null or array. %s given.',
                    FCMMessage::class,
                    'data',
                    PayloadData::class,
                    PayloadDataBuilder::class,
                    'string'
                ),
                $e->getMessage()
            );
            throw $e;
        }
    }

    /** @test */
    public function it_construct_with_notification_from_builder()
    {
        $message = new FCMMessage();

        $this->assertNull($message->getNotification());

        $notification = new PayloadNotificationBuilder();
        $notification->setTitle('test_title')
                     ->setBody('test_body')
                     ->setSound('test_sound')
                     ->setBadge('test_badge')
                     ->setTag('test_tag')
                     ->setColor('test_color')
                     ->setClickAction('test_click_action')
                     ->setBodyLocationKey('test_body_key')
                     ->setBodyLocationArgs('[ body0, body1 ]')
                     ->setTitleLocationKey('test_title_key')
                     ->setTitleLocationArgs('[ title0, title1 ]')
                     ->setIcon('test_icon');

        $message->notification($notification);

        $this->assertInstanceOf(PayloadNotification::class, $message->getNotification());
    }

    /** @test */
    public function it_construct_with_notification_from_instance()
    {
        $message = new FCMMessage();

        $this->assertNull($message->getNotification());

        $notification = new PayloadNotificationBuilder();
        $notification->setTitle('test_title')
                     ->setBody('test_body')
                     ->setSound('test_sound')
                     ->setBadge('test_badge')
                     ->setTag('test_tag')
                     ->setColor('test_color')
                     ->setClickAction('test_click_action')
                     ->setBodyLocationKey('test_body_key')
                     ->setBodyLocationArgs('[ body0, body1 ]')
                     ->setTitleLocationKey('test_title_key')
                     ->setTitleLocationArgs('[ title0, title1 ]')
                     ->setIcon('test_icon');

        $message->notification($notification->build());

        $this->assertInstanceOf(PayloadNotification::class, $message->getNotification());
    }

    /** @test */
    public function it_construct_with_notification_from_array()
    {
        $message = new FCMMessage();

        $this->assertNull($message->getNotification());

        $message->notification([
            'title' => 'test_title',
            'body' => 'test_body',
            'badge' => 'test_badge',
            'sound' => 'test_sound',
            'tag' => 'test_tag',
            'color' => 'test_color',
            'click_action' => 'test_click_action',
            'body_loc_key' => 'test_body_key',
            'body_loc_args' => '[ body0, body1 ]',
            'title_loc_key' => 'test_title_key',
            'title_loc_args' => '[ title0, title1 ]',
            'icon' => 'test_icon',
        ]);

        $this->assertInstanceOf(PayloadNotification::class, $message->getNotification());
    }

    /** @test */
    public function it_construct_with_notification_and_throw_exception()
    {
        $this->setExpectedException(InvalidArgumentException::class);
        try {
            (new FCMMessage())->notification('foo');
        } catch (InvalidArgumentException $e) {
            $this->assertEquals(
                sprintf(
                    'The argument for %s::%s must be instanceof %s, %s, null or array. %s given.',
                    FCMMessage::class,
                    'notification',
                    PayloadNotification::class,
                    PayloadNotificationBuilder::class,
                    'string'
                ),
                $e->getMessage()
            );
            throw $e;
        }
    }
}
