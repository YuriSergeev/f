<?php
namespace blog;

use blog\Select;
use blog\Insert;
use blog\Update;
use blog\Delete;

class QueryFactory
{
    public static function createSelect($table = null, array $columns = null)
    {
        return new Select($table, $columns);
    }

    public static function createInsert($table = null, array $values = null)
    {
        return new Insert($table, $values);
    }

    public static function createUpdate($table = null, array $values = null)
    {
        return new Update($table, $values);
    }

    public static function createDelete($table = null)
    {
        return new Delete($table);
    }
}
