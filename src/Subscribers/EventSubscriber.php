<?php

namespace ByZer0\QueryLog\Subscribers;

use ByZer0\QueryLog\Contracts\QueryFormatter;
use Illuminate\Contracts\Events\Dispatcher;
use Psr\Log\LoggerInterface;

/**
 * Class EventSubscriber
 *
 * Basic class for database events subscribers.
 */
abstract class EventSubscriber
{
    /**
     * @var QueryFormatter
     */
    protected $formatter;
    /**
     * @var string
     */
    protected $level;
    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * QueryExecutedSubscriber constructor.
     *
     * @param QueryFormatter $formatter
     * @param LoggerInterface $logger
     * @param string $level
     */
    public function __construct(QueryFormatter $formatter, LoggerInterface $logger, string $level)
    {
        $this->formatter = $formatter;
        $this->logger    = $logger;
        $this->level     = $level;
    }

    /**
     * Subscribe to database events.
     *
     * @param Dispatcher $events
     */
    abstract public function subscribe(Dispatcher $events): void;
}
