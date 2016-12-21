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
    public function it_construct_with_options_merge_if_array()
    {
        $message = new FCMMessage();

        $this->assertNull($message->getOptions());

        $options = new OptionsBuilder();
        $options->setCollapseKey('collapseKey')
                ->setContentAvailable(false);
        $message->options($options);
        $message->options([
            'content_available' => true,
            'priority' => OptionsPriorities::high,
            'delay_while_idle' => true,
            'dry_run' => true,
            'restricted_package_name' => 'customPackageName',
            'time_to_live' => 200,
        ]);

        $this->assertEquals([
            'collapse_key' => 'collapseKey',
            'content_available' => true,
            'priority' => OptionsPriorities::high,
            'delay_while_idle' => true,
            'dry_run' => true,
            'restricted_package_name' => 'customPackageName',
            'time_to_live' => 200,
        ], $message->getOptions()->toArray());
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
    public function it_construct_with_data_merge_if_array()
    {
        $message = new FCMMessage();

        $this->assertNull($message->getData());

        $data = new PayloadDataBuilder();
        $data->addData(['foo' => 'bar'])
             ->addData(['baz' => true]);
        $message->data($data);

        $message->data([
            'baz' => false,
        ]);

        $this->assertEquals([
            'foo' => 'bar',
            'baz' => false,
        ], $message->getData()->toArray());
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
    public function it_construct_with_notification_merge_if_array()
    {
        $message = new FCMMessage();

        $this->assertNull($message->getNotification());

        $notification = new PayloadNotificationBuilder();
        $notification->setTitle('test_title')
                     ->setBody('test_body')
                     ->setSound('test_sound')
                     ->setBadge('test_badge')
                     ->setTag('test_tag');
        $message->notification($notification);

        $message->notification([
            'color' => 'test_color',
            'click_action' => 'test_click_action',
            'body_loc_key' => 'test_body_key',
            'body_loc_args' => '[ body0, body1 ]',
            'title_loc_key' => 'test_title_key',
            'title_loc_args' => '[ title0, title1 ]',
            'icon' => 'test_icon',
        ]);
        $this->assertEquals([
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
        ], $message->getNotification()->toArray());
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

    /** @test */
    public function it_construct_with_proxy_methods()
    {
        $message = new FCMMessage();
        $message->setCollapseKey('collapseKey')
                ->setContentAvailable(true)
                ->setPriority(OptionsPriorities::high)
                ->setDelayWhileIdle(true)
                ->setDryRun(true)
                ->setRestrictedPackageName('customPackageName')
                ->setTimeToLive(200)
                ->setTitle('test_title')
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

        $this->assertEquals($message->getCollapseKey(), 'collapseKey');
        $this->assertEquals($message->isContentAvailable(), true);
        $this->assertEquals($message->getPriority(), OptionsPriorities::high);
        $this->assertEquals($message->isDelayWhileIdle(), true);
        $this->assertEquals($message->isDryRun(), true);
        $this->assertEquals($message->getRestrictedPackageName(), 'customPackageName');
        $this->assertEquals($message->getTimeToLive(), 200);
        $this->assertEquals($message->getTitle(), 'test_title');
        $this->assertEquals($message->getBody(), 'test_body');
        $this->assertEquals($message->getSound(), 'test_sound');
        $this->assertEquals($message->getBadge(), 'test_badge');
        $this->assertEquals($message->getTag(), 'test_tag');
        $this->assertEquals($message->getColor(), 'test_color');
        $this->assertEquals($message->getClickAction(), 'test_click_action');
        $this->assertEquals($message->getBodyLocationKey(), 'test_body_key');
        $this->assertEquals($message->getBodyLocationArgs(), '[ body0, body1 ]');
        $this->assertEquals($message->getTitleLocationKey(), 'test_title_key');
        $this->assertEquals($message->getTitleLocationArgs(), '[ title0, title1 ]');
        $this->assertEquals($message->getIcon(), 'test_icon');
    }
}
