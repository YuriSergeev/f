<?php

namespace builder;

use builder\SqlBuilder;

class MyQueryBuilder
{
    public static function sqlbuilder($driver, $config)
    {
        return new SqlBuilder($config);
    }
}
