<?php

namespace ByZer0\QueryLog;

use ByZer0\QueryLog\Contracts\QueryFormatter as FormatterContract;
use ByZer0\QueryLog\Subscribers\QueryExecutedSubscriber;
use ByZer0\QueryLog\Subscribers\TransactionSubscriber;
use Illuminate\Contracts\Container\Container;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\ServiceProvider;
use Psr\Log\LoggerInterface;

class QueryLogServiceProvider extends ServiceProvider
{
    /**
     * Array of available event subscribers.
     *
     * @var array
     */
    protected $subscribers = [
        'query' => QueryExecutedSubscriber::class,
        'transaction' => TransactionSubscriber::class,
    ];

    /**
     * Register log services.
     */
    public function register(): void
    {
        $this->publishConfig();

        $this->registerFormatter();
        $this->registerSubscribers();

        $this->subscribeToEvents();
    }

    /**
     * Path to default configuration file.
     *
     * @return string
     */
    protected function configPath(): string
    {
        return __DIR__.'/../config/query-log.php';
    }

    /**
     * Publish configuration file.
     */
    protected function publishConfig(): void
    {
        $configPath = $this->configPath();

        if ($this->app->runningInConsole()) {
            $this->publishes([
                $configPath => config_path('query-log.php'),
            ], 'query-log-config');
        }

        $this->mergeConfigFrom($configPath, 'query-log');
    }

    /**
     * Register subscriber classes into container.
     */
    protected function registerSubscribers(): void
    {
        foreach ($this->subscribers as $type => $subscriber) {
            $this->app->singleton($subscriber, function (Container $app) use ($type, $subscriber) {

                /** @var \Illuminate\Contracts\Config\Repository $config */
                $config = $app->get('config');

                $formatter = $app->get(FormatterContract::class);
                $logger = $app->get(LoggerInterface::class)->stack($config->get("query-log.events.{$type}.channels"));
                $level = $config->get("query-log.events.{$type}.level");

                return new $subscriber($formatter, $logger, $level);
            });
        }
    }

    /**
     * Subscribe all enabled listeners to database events.
     */
    protected function subscribeToEvents(): void
    {
        $this->app->extend('events', function (Dispatcher $events, Container $app) {

            /** @var \Illuminate\Contracts\Config\Repository $config */
            $config = $app->get('config');

            foreach ($this->subscribers as $type => $subscriber) {
                if (!$config->get("query-log.events.{$type}.enabled")) {
                    continue;
                }

                $events->subscribe($subscriber);
            }

            return $events;
        });
    }

    /**
     * Register query formatter into container.
     */
    protected function registerFormatter(): void
    {
        $this->app->singleton(FormatterContract::class, function (Container $app) {
            /** @var \Illuminate\Contracts\Config\Repository $config */
            $config = $app->get('config');

            $formatterClass = $config->get('query-log.formatter');

            return $app->make($formatterClass);
        });
    }
}
