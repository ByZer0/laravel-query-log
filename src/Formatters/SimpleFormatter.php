<?php

namespace ByZer0\QueryLog\Formatters;

use ByZer0\QueryLog\Contracts\QueryFormatter;
use Illuminate\Database\Connection;

/**
 * Class SimpleFormatter
 *
 * Simple formatter does nothing and just returns SQL as is.
 */
class SimpleFormatter implements QueryFormatter
{
    /** {@inheritdoc} */
    public function format(Connection $connection, string $query, array $bindings): string
    {
        return $query;
    }
}
