<?php

namespace ByZer0\QueryLog\Formatters;

use ByZer0\QueryLog\Contracts\QueryFormatter as FormatterContract;
use Illuminate\Database\Connection;

/**
 * Class SubstituteBindingsFormatter
 *
 * SubstituteBindingsFormatter replaces all parameter placeholders
 * with appropriate binding values.
 */
class SubstituteBindingsFormatter implements FormatterContract
{
    /** {@inheritdoc} */
    public function format(Connection $connection, string $query, array $bindings): string
    {
        foreach ($connection->prepareBindings($bindings) as $name => $value) {
            $query = $this->substituteBinding($connection, $query, $name, $value);
        }

        return $query;
    }

    /**
     * Substitute single parameter values in query.
     *
     * @param Connection $connection
     * @param string $query
     * @param int|string $name
     * @param mixed $value
     *
     * @return string
     */
    private function substituteBinding(Connection $connection, string $query, $name, $value): string
    {
        if (is_numeric($name)) {
            $regex = "/\?(?=(?:[^'\\\']*'[^'\\\']*')*[^'\\\']*$)/";
        } else {
            $regex = "/:{$name}(?=(?:[^'\\\']*'[^'\\\']*')*[^'\\\']*$)/";
        }

        return preg_replace($regex, $connection->getPdo()->quote($value), $query, 1);
    }
}
