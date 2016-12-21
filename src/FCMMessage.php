<?php

namespace NotificationChannels\FCM;

use Illuminate\Support\Arr;
use LaravelFCM\Message\Options;
use LaravelFCM\Message\PayloadData;
use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\OptionsPriorities;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotification;
use LaravelFCM\Message\PayloadNotificationBuilder;
use NotificationChannels\FCM\Exceptions\InvalidArgumentException;

class FCMMessage
{
    /**
     * @var array
     */
    const OPTIONS_SETTERS = [
        'setDryRun'                => 'dry_run',
        'setPriority'              => 'priority',
        'setTimeToLive'            => 'time_to_live',
        'setCollapseKey'           => 'collapse_key',
        'setDelayWhileIdle'        => 'delay_while_idle',
        'setContentAvailable'      => 'content_available',
        'setRestrictedPackageName' => 'restricted_package_name',
    ];

    /**
     * @var array
     */
    const OPTIONS_GETTERS = [
        'isDryRun'                 => 'dry_run',
        'getPriority'              => 'priority',
        'getTimeToLive'            => 'time_to_live',
        'getCollapseKey'           => 'collapse_key',
        'isDelayWhileIdle'         => 'delay_while_idle',
        'isContentAvailable'       => 'content_available',
        'getRestrictedPackageName' => 'restricted_package_name',
    ];

    /**
     * @var array
     */
    const NOTIFICATION_SETTERS = [
        'setTag'               => 'tag',
        'setBody'              => 'body',
        'setIcon'              => 'icon',
        'setTitle'             => 'title',
        'setSound'             => 'sound',
        'setBadge'             => 'badge',
        'setColor'             => 'color',
        'setClickAction'       => 'click_action',
        'setBodyLocationKey'   => 'body_loc_key',
        'setBodyLocationArgs'  => 'body_loc_args',
        'setTitleLocationKey'  => 'title_loc_key',
        'setTitleLocationArgs' => 'title_loc_args',
    ];

    /**
     * @var array
     */
    const NOTIFICATION_GETTERS = [
        'getTag'               => 'tag',
        'getBody'              => 'body',
        'getIcon'              => 'icon',
        'getTitle'             => 'title',
        'getSound'             => 'sound',
        'getBadge'             => 'badge',
        'getColor'             => 'color',
        'getClickAction'       => 'click_action',
        'getBodyLocationKey'   => 'body_loc_key',
        'getBodyLocationArgs'  => 'body_loc_args',
        'getTitleLocationKey'  => 'title_loc_key',
        'getTitleLocationArgs' => 'title_loc_args',
    ];

    /**
     * @var array
     */
    const OPTIONS_MAP = [
        'dry_run'                 => 'setDryRun',
        'priority'                => 'setPriority',
        'time_to_live'            => 'setTimeToLive',
        'collapse_key'            => 'setCollapseKey',
        'delay_while_idle'        => 'setDelayWhileIdle',
        'content_available'       => 'setContentAvailable',
        'restricted_package_name' => 'setRestrictedPackageName',
    ];

    /**
     * @var array
     */
    const NOTIFICATION_MAP = [
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
     * Call method.
     *
     * @param  string $method
     * @param  mixed $value
     *
     * @return mixed
     * @throws \BadMethodCallException
     */
    public function __call($method, array $arguments)
    {
        if (Arr::has(static::OPTIONS_SETTERS, $method)) {
            $this->options([
                Arr::get(static::OPTIONS_SETTERS, $method) => Arr::get($arguments, 0),
            ]);

            return $this;
        } elseif (Arr::has(static::OPTIONS_GETTERS, $method)) {
            $value = null;
            if ($this->options && $this->options instanceof Options) {
                $property = Arr::get(static::OPTIONS_GETTERS, $method);

                $value = Arr::get($this->options->toArray(), $property);
            }

            return $value;
        } elseif (Arr::has(static::NOTIFICATION_SETTERS, $method)) {
            $this->notification([
                Arr::get(static::NOTIFICATION_SETTERS, $method) => Arr::get($arguments, 0),
            ]);

            return $this;
        } elseif (Arr::has(static::NOTIFICATION_GETTERS, $method)) {
            $value = null;
            if ($this->notification && $this->notification instanceof PayloadNotification) {
                $property = Arr::get(static::NOTIFICATION_GETTERS, $method);

                $value = Arr::get($this->notification->toArray(), $property);
            }

            return $value;
        } else {
            throw new \BadMethodCallException("Method {$method} does not exist.");
        }
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
            if ($this->options && $this->options instanceof Options) {
                $options = array_merge($this->options->toArray(), $options);
            }
            if (! Arr::has($options, 'priority')) {
                $options['priority'] = OptionsPriorities::normal;
            }
            $builder = new OptionsBuilder();
            $options = static::populateBuilder($builder, static::OPTIONS_MAP, $options);
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
            if ($this->notification && $this->notification instanceof PayloadNotification) {
                $notification = array_merge($this->notification->toArray(), $notification);
            }
            $builder = new PayloadNotificationBuilder();
            $notification = static::populateBuilder($builder, static::NOTIFICATION_MAP, $notification);
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
            if ($this->data && $this->data instanceof PayloadData) {
                $data = array_merge($this->data->toArray(), $data);
            }
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
