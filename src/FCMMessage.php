<?php

namespace NotificationChannels\FCM;

use Illuminate\Support\Arr;
use LaravelFCM\Message\Options;
use LaravelFCM\Message\PayloadData;
use LaravelFCM\Message\PayloadNotification;
use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;
use NotificationChannels\FCM\Exceptions\InvalidArgumentException;

class FCMMessage
{
    /**
     * @var mixed
     */
    protected $recipient;

    /**
     * @var mixed
     */
    protected $options;

    /**
     * @var mixed
     */
    protected $notification;

    /**
     * @var mixed
     */
    protected $data;

    /**
     * Message Constructor.
     *
     * @param mixed $options
     * @param mixed $notification
     * @param mixed $data
     */
    public function __construct($options = null, $notification = null, $data = null)
    {
        $this->options($options);
        $this->notification($notification);
        $this->data($data);
    }

    /**
     * Create Message.
     *
     * @param mixed $options
     * @param mixed $notification
     * @param mixed $data
     */
    public static function create($options = null, $notification = null, $data = null)
    {
        return new static(...func_get_args());
    }

    /**
     * Recipient.
     *
     * @return $this
     */
    public function to($recipient)
    {
        $this->recipient = $recipient;

        return $this;
    }

    /**
     * Get Recipient.
     *
     * @return mixed
     */
    public function getRecipient()
    {
        return $this->recipient;
    }

    /**
     * Make Invalid Argument Exception.
     *
     * @param  mixed  $data
     * @param  string $method
     * @param  string $container
     * @param  string $builder
     * @return \NotificationChannels\FCM\Exceptions\InvalidArgumentException
     */
    protected static function makeInvalidArgumentException($data, $method, $container, $builder)
    {
        $type = gettype($data);
        if (is_object($data)) {
            $type = get_class($data);
        }

        return new InvalidArgumentException(
           'The argument for %s::%s must be instanceof %s, %s, null or array. %s given.',
            [
                self::class, $method, $container, $builder, $type,
            ]
        );
    }

    /**
     * Populate Builder.
     *
     * @param  object $builder
     * @param  array  $map
     * @param  array  $data
     * @return object
     */
    protected static function populateBuilder($builder, array $map, array $data)
    {
        foreach ($map as $key => $method) {
            $value = Arr::get($data, $key);
            $builder->{$method}($value);
        }

        return $builder;
    }

    /**
     * Set Options.
     *
     * @param mixed $options
     * @return
     */
    public function options($options = null)
    {
        if (is_array($options)) {
            $map = [
                'dry_run'                 => 'setDryRun',
                'priority'                => 'setPriority',
                'time_to_live'            => 'setTimeToLive',
                'collapse_key'            => 'setCollapseKey',
                'delay_while_idle'        => 'setDelayWhileIdle',
                'content_available'       => 'setContentAvailable',
                'restricted_package_name' => 'setRestrictedPackageName',
            ];
            $builder = new OptionsBuilder();
            $options = static::populateBuilder($builder, $map, $options);
        }
        if ($options instanceof Options) {
            $this->options = $options;
        } elseif ($options instanceof OptionsBuilder) {
            $this->options = $options->build();
        } elseif (! is_null($options)) {
            throw static::makeInvalidArgumentException(
                $options,
                'options',
                Options::class,
                OptionsBuilder::class
            );
        }

        return $this;
    }

    /**
     * Set Notification.
     *
     * @param  mixed $notification
     * @return $this
     */
    public function notification($notification = null)
    {
        if (is_array($notification)) {
            $map = [
                'tag'            => 'setTag',
                'body'           => 'setBody',
                'icon'           => 'setIcon',
                'title'          => 'setTitle',
                'sound'          => 'setSound',
                'badge'          => 'setBadge',
                'color'          => 'setColor',
                'click_action'   => 'setClickAction',
                'body_loc_key'   => 'setBodyLocationKey',
                'body_loc_args'  => 'setBodyLocationArgs',
                'title_loc_key'  => 'setTitleLocationKey',
                'title_loc_args' => 'setTitleLocationArgs',
            ];
            $builder = new PayloadNotificationBuilder();
            $notification = static::populateBuilder($builder, $map, $notification);
        }
        if ($notification instanceof PayloadNotification) {
            $this->notification = $notification;
        } elseif ($notification instanceof PayloadNotificationBuilder) {
            $this->notification = $notification->build();
        } elseif (! is_null($notification)) {
            throw static::makeInvalidArgumentException(
                $notification,
                'notification',
                PayloadNotification::class,
                PayloadNotificationBuilder::class
            );
        }

        return $this;
    }

    /**
     * Set Data.
     *
     * @param mixed $data
     * @return $this
     */
    public function data($data = null)
    {
        if (is_array($data)) {
            $data = (new PayloadDataBuilder())->setData($data);
        }

        if ($data instanceof PayloadData) {
            $this->data = $data;
        } elseif ($data instanceof PayloadDataBuilder) {
            $this->data = $data->build();
        } elseif (! is_null($data)) {
            throw static::makeInvalidArgumentException(
                $data,
                'data',
                PayloadData::class,
                PayloadDataBuilder::class
            );
        }

        return $this;
    }

    public function getData()
    {
        return $this->data;
    }

    public function getOptions()
    {
        return $this->options;
    }

    public function getNotification()
    {
        return $this->notification;
    }

    public function getArgs()
    {
        return [
            $this->getRecipient(),
            $this->getOptions(),
            $this->getNotification(),
            $this->getData(),
        ];
    }

    /**
     * Determine if recipient is not given.
     *
     * @return bool
     */
    public function recipientNotGiven()
    {
        return ! $this->recipient;
    }
}
