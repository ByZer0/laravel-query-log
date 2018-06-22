<?php

namespace ByZer0\QueryLog\Subscribers;

use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Database\Events\QueryExecuted;

/**
 * Class QueryExecutedSubscriber
 *
 * This class listens for QueryExecuted event and logs executed query.
 */
class QueryExecutedSubscriber extends EventSubscriber
{
    /** {@inheritdoc} */
    public function subscribe(Dispatcher $events): void
    {
        $events->listen(QueryExecuted::class, static::class.'@'.'handle');
    }

    /**
     * Log query executed event.
     *
     * @param QueryExecuted $event
     */
    public function handle(QueryExecuted $event): void
    {
        $this->logger->log($this->level, 'Query executed.', [
            'connection' => $event->connectionName,
            'query' => $this->formatter->format($event->connection, $event->sql, $event->bindings),
            'type' => 'query',
            'time' => $event->time,
            'bindings' => $event->connection->prepareBindings($event->bindings),
        ]);
    }
}
