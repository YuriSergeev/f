<?php

class MyQueryBuilder
{
    public static function sqlbuilder($config)
    {
        $check = PDO::getAvailableDrivers();
        if(in_array($config['driver'] ,$check))
        {
            return new SqlBuilder($config);
        } else {
            echo "Такого драйвера не существует";
        }

    }
}
