<?php

namespace ByZer0\QueryLog\Contracts;

use Illuminate\Database\Connection;

/**
 * Interface QueryFormatter
 *
 * Common interface that is used to prepare query to be logged.
 */
interface QueryFormatter
{
    /**
     * Format SQL before passing it to logger.
     *
     * @param Connection $connection
     * @param string $query
     * @param array $bindings
     *
     * @return string
     */
    public function format(Connection $connection, string $query, array $bindings): string;
}
