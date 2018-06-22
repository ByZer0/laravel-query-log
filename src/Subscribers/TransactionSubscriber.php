<?php

namespace ByZer0\QueryLog\Subscribers;

use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Database\Events\ConnectionEvent;
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Database\Events\TransactionBeginning;
use Illuminate\Database\Events\TransactionCommitted;
use Illuminate\Database\Events\TransactionRolledBack;

/**
 * Class QueryExecutedSubscriber
 *
 * This class listens for transaction events.
 */
class TransactionSubscriber extends EventSubscriber
{
    /** {@inheritdoc} */
    public function subscribe(Dispatcher $events): void
    {
        $events->listen(TransactionBeginning::class, static::class.'@'.'handleBegin');
        $events->listen(TransactionCommitted::class, static::class.'@'.'handleCommit');
        $events->listen(TransactionRolledBack::class, static::class.'@'.'handleRollback');
    }

    /**
     * Log transaction start event.
     *
     * @param TransactionBeginning $event
     */
    public function handleBegin(TransactionBeginning $event): void
    {
        $this->log($event, 'Transaction begin.');
    }

    /**
     * Log transaction commit event.
     *
     * @param TransactionCommitted $event
     */
    public function handleCommit(TransactionCommitted $event): void
    {
        $this->log($event, 'Transaction commit.');
    }

    /**
     * Log transaction rollback event.
     *
     * @param TransactionRolledBack $event
     */
    public function handleRollback(TransactionRolledBack $event): void
    {
        $this->log($event, 'Transaction rollback.');
    }

    /**
     * Log transaction event.
     *
     * @param ConnectionEvent $event
     * @param string $message
     */
    protected function log(ConnectionEvent $event, string $message): void
    {
        $this->logger->log($this->level, $message, [
            'connection' => $event->connectionName,
            'query' => '',
            'type' => 'transaction',
            'time' => 0,
            'bindings' => [],
        ]);
    }
}
